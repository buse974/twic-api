<?php
namespace Application\Service;

use Dal\Service\AbstractService;
use Zend\Json\Server\Request;
use Zend\Http\Client;

class Event extends AbstractService
{
    static private $id=0;
    
    const TARGET_TYPE_USER = 'user';
    const TARGET_TYPE_GLOBAL = 'global';
    const TARGET_TYPE_SCHOOL = 'school';

    /**
     * create event
     *
     * @param string $event            
     * @param string $source            
     * @param string $object            
     * @param array $user            
     * @throws \Exception
     * @return integer
     */
    public function create($event, $source, $object, $user, $target)
    {
        $date = (new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s');
        $m_event = $this->getModel()
            ->setEvent($event)
            ->setSource(json_encode($source))
            ->setObject(json_encode($object))
            ->setTarget($target)
            ->setDate($date);
        
        if ($this->getMapper()->insert($m_event) <= 0) {
            throw new \Exception('error insert event');
        }
        
        $event_id = $this->getMapper()->getLastInsertValue();
        $this->getServiceEventUser()->add($user, $event_id);
        
        $this->sendRequest($user, array(
            'event' => $event,
            'source' => $source,
            'date' => (new \DateTime($date))->format('Y-m-d\TH:i:s\Z'),
            'object' => $object
        ), $target);
        return $event_id;
    }

    public function sendRequest($users, $notification, $target)
    {
        $request = new Request();
        $request->setMethod('notification.publish')->setParams(array(
            'notification' => $notification,
            'users' => $users,
            'type' => $target
        ))
        ->setId(++self::$id)
        ->setVersion('2.0');
        
        $client = new \Zend\Json\Server\Client($this->serviceLocator->get('config')['node']['addr'], $this->getClient());
        
        try {
            $client->doRequest($request);
        } catch (\Exception $e) {
            syslog(1,$e->getMessage());
        }
    }
    
    /**
     * 
     * @return \Zend\Http\Client
     */
    public function getClient()
    {
        $client = new Client();
        $client->setOptions($this->serviceLocator->get('config')['http-adapter']);
        
        return $client;
    }

    /**
     * @invokable
     */
    public function getList($filter = null, $events = null, $user = null)
    {
        $mapper = $this->getMapper();
        if(null === $user){
            $user = $this->getServiceUser()->getIdentity()['id'];            
        }
      
        $res_event = $mapper->usePaginator($filter)->getList($user, $events);
        $ar_event = $res_event->toArray();
        foreach($ar_event as &$event){
            $event['source'] = json_decode($event['source']);
            $event['object'] = json_decode($event['object']);
        }
        return ['list' => $ar_event,'count' => $mapper->count()];
    }
    
    /**
     * 
     * @param unknown $id
     * @return \Application\Model\Event
     */
    public function get($id)
    {
        $user = $this->getServiceUser()->getIdentity()['id'];
        $m_event = $this->getMapper()->getList($user, null, $id)->current();
        $m_event->setSource(json_decode($m_event->getSource()));
        $m_event->setObject(json_decode($m_event->getObject()));
        
        return $m_event;
    }
    
    // event
    public function userPublication($feed)
    {
        return $this->create('user.publication', $this->getDataUser(), $this->getDataFeed($feed), $this->getDataUserContact(), self::TARGET_TYPE_USER);
    }

    public function userLike($event)
    {
        return $this->create('user.like', $this->getDataUser(), $this->getDataEvent($event), $this->getDataUserContact(), self::TARGET_TYPE_USER);
    }

    public function userAddConnection($user, $contact)
    {
        return $this->create(
            'user.addconnection', 
            $this->getDataUser($user), 
            $this->getDataUser($contact), 
            $this->getDataUserContact($user), 
            self::TARGET_TYPE_USER
        );
    }

    public function studentSubmitAssignment($item_assignment)
    {
        $m_item_assignment = $this->getServiceItemAssignment()->get($item_assignment);
        
        $res_user = $m_item_assignment->getItemProg()
            ->getItem()
            ->getCourse()
            ->getInstructor();
        
        $users = [];
        foreach ($res_user as $m_user) {
            $users[] = $m_user->getId();
        }
        
        return $this->create('student.submit.assignment', $this->getDataUser(), $this->getDataAssignment($m_item_assignment), $users, self::TARGET_TYPE_USER);
    }

    public function assignmentGraded($item_assignment)
    {
        $m_item_assignment = $this->getServiceItemAssignment()->get($item_assignment);
        
        $res_user = $m_item_assignment->getStudents();
        $users = [];
        foreach ($res_user as $m_user) {
            $users[] = $m_user->getId();
        }
        
        return $this->create('assignment.graded', $this->getDataUser(), $this->getDataAssignmentGrade($m_item_assignment), $users, self::TARGET_TYPE_USER);
    }

    public function assignmentCommented($item_assignment, $item_assignment_comment)
    {
        $m_item_assignment = $this->getServiceItemAssignment()->get($item_assignment);
        $m_assignment_comment = $this->getServiceItemAssignmentComment()->get($item_assignment_comment);
        
        $res_user = $m_item_assignment->getStudents();
        $users = [];
        foreach ($res_user as $m_user) {
            $users[] = $m_user->getId();
        }
        
        return $this->create('assignment.commented', $this->getDataUser(), $this->getDataAssignmentComment($m_item_assignment, $m_assignment_comment), $users, self::TARGET_TYPE_USER);
    }

    public function threadNew($thread)
    {
        $m_thread = $this->getServiceThread()->get($thread);
        
        $users = $this->getDataUserByCourse($m_thread->getCourse()
            ->getId());
        
        return $this->create('thread.new', $this->getDataUser(), $this->getDataThread($m_thread), $users, self::TARGET_TYPE_USER);
    }

    public function threadMessage($thread_message)
    {
        $m_thread_message = $this->getServiceThreadMessage()->get($thread_message);
        
        $users = $this->getDataUserByCourse($m_thread_message->getThread()
            ->getCourseId());
        
        return $this->create('thread.message', $this->getDataUser(), $this->getDataThreadMessage($m_thread_message), $users, self::TARGET_TYPE_USER);
    }

    public function recordAvailable($item_prog, $videoconf_archive)
    {
        $m_item_prog = $this->getServiceItemProg()->get($item_prog);
        $m_videoconf_archive = $this->getServiceVideoconfArchive()->get($videoconf_archive);
        
        return $this->create('record.available', $this->getDataItemProg($m_item_prog), $this->getDataVideoArchive($m_videoconf_archive), $this->getDataUserByItemProg($m_item_prog->getId()), self::TARGET_TYPE_USER);
    }

    public function eqcqAvailable($item_prog, $videoconf_archive)
    {
        $m_item_prog = $this->getServiceItemProg()->get($item_prog);
        $m_videoconf_archive = $this->getServiceVideoconfArchive()->get($videoconf_archive);
        
        return $this->create('eqcq.available', $this->getDataItemProgWihtUser($m_item_prog), [], $this->getDataUserByItemProg($m_item_prog->getId()), self::TARGET_TYPE_USER);
    }

    public function courseUpdated($course, $dataupdated)
    {
        return $this->create('course.updated', $this->getDataUser(), $this->getDataCourseUpdate($course, $dataupdated), $this->getDataUserByCourse($course), self::TARGET_TYPE_USER);
    }

    public function courseMaterialAdded($course, $material)
    {
        return $this->create('course.material_added', $this->getDataUser(), $this->getDataCourseAddMaterial($course, $material), $this->getDataUserByCourse($course), self::TARGET_TYPE_USER);
    }

    public function programmationNew($item_prog)
    {
        return $this->create('programmation.new', $this->getDataUser(), $this->getDataProgrammation($item_prog), $this->getDataUserByItemProg($item_prog), self::TARGET_TYPE_USER);
    }

    public function programmationUpdated($item_prog)
    {
        return $this->create('programmation.updated', $this->getDataUser(), $this->getDataProgrammation($item_prog), $this->getDataUserByItemProg($item_prog), self::TARGET_TYPE_USER);
    }

    public function profileUpdated($user, $dataprofile)
    {
        return $this->create('profile.updated', $this->getDataUser(), $this->getDataUpdateProfile($user, $dataprofile), $this->getDataUserContact(), self::TARGET_TYPE_USER);
    }

    public function profileNewresume($resume)
    {
        return $this->create('profile.newresume', $this->getDataUser(), $this->getDataResume($resume), $this->getDataUserContact(), self::TARGET_TYPE_USER);
    }

    public function userRequestconnection($user)
    {
        return $this->create('user.requestconnection', $this->getDataUser(), $this->getDataUser($user), [$user], self::TARGET_TYPE_USER);
    }

    public function schoolNew($school)
    {
        return $this->create('school.new', [], $this->getDataSchool($school), [], self::TARGET_TYPE_GLOBAL);
    }
    
    // ------------- DATA OBJECT -------------------
    public function getDataSchool($school)
    {
        $m_school = $this->getServiceSchool()->get($school);
        
        return ['id' => $m_school->getId(),'name' => 'school','data' => ['id' => $m_school->getId(),'name' => $m_school->getName(),'short_name' => $m_school->getShortName(),'logo' => $m_school->getLogo()]];
    }

    public function getDataResume($resume)
    {
        $m_resume = $this->getServiceResume()->getById($resume);
        
        return ['id' => $resume,'name' => 'resume','data' => ['start_date' => $m_resume->getStartDate(),'end_date' => $m_resume->getEndDate(),'address' => $m_resume->getAddress(),'title' => $m_resume->getTitle(),'subtitle' => $m_resume->getSubtitle(),'logo' => $m_resume->getLogo(),'description' => $m_resume->getDescription(),'type' => $m_resume->getType()]];
    }

    public function getDataUpdateProfile($user, $dataupdated)
    {
        if (isset($dataupdated['id'])) {
            unset($dataupdated['id']);
        }
        
        return ['id' => $user,'name' => 'user','data' => ['updated' => array_keys($dataupdated)]];
    }

    public function getDataProgrammation($item_prog)
    {
        $m_item_prog = $this->getServiceItemProg()->get($item_prog);
        
        return ['id' => $m_item_prog->getId(),'name' => 'programmation','data' => ['start_date' => $m_item_prog->getStartDate(),'due_date' => $m_item_prog->getDueDate(),'item' => ['id' => $m_item_prog->getItem()->getId(),'title' => $m_item_prog->getItem()->getTitle(),'type' => $m_item_prog->getItem()->getType(),'duration' => $m_item_prog->getItem()->getDuration(),'course' => ['id' => $m_item_prog->getItem()
            ->getCourse()
            ->getId(),'title' => $m_item_prog->getItem()
            ->getCourse()
            ->getId()]]]];
    }

    public function getDataCourseAddMaterial($course, $material)
    {
        $m_course = $this->getServiceCourse()->get($course);
        $m_material_document = $this->getServiceMaterialDocument()->get($material);
        
        return ['id' => $course,'name' => 'course','data' => ['title' => $m_course->getTitle(),'picture' => $m_course->getPicture(),'material' => ['type' => $m_material_document->getType(),'title' => $m_material_document->getTitle(),'author' => $m_material_document->getAuthor(),'link' => $m_material_document->getLink(),'source' => $m_material_document->getSource(),'token' => $m_material_document->getToken()]]];
    }

    public function getDataCourseUpdate($course, $dataupdated)
    {
        $m_course = $this->getServiceCourse()->get($course);
        
        if (isset($dataupdated['id'])) {
            unset($dataupdated['id']);
        }
        
        return ['id' => $course,'name' => 'course','data' => ['title' => $m_course->getTitle(),'picture' => $m_course->getPicture(),'updated' => array_keys($dataupdated)]];
    }

    public function getDataVideoArchive(\Application\Model\VideoconfArchive $m_videoconf_archive)
    {
        return ['id' => $m_videoconf_archive->getId(),'name' => 'archive','data' => ['archive_link' => $m_videoconf_archive->getArchiveLink()]];
    }

    public function getDataItemProgWihtUser(\Application\Model\ItemProg $m_item_prog)
    {
        $res_user = $this->getServiceUser()->getListByItemProg($m_item_prog->getId());
        
        $users = [];
        foreach ($res_user as $m_user) {
            $users[] = ['firstname' => $m_user->getFirstname(),'lastname' => $m_user->getLastname(),'avatar' => $m_user->getAvatar()];
        }
        
        return ['id' => $m_item_prog->getId(),'name' => 'programming','data' => ['start_date' => $m_item_prog->getStartDate(),'item' => ['id' => $m_item_prog->getItem()->getId(),'title' => $m_item_prog->getItem()->getTitle(),'type' => $m_item_prog->getItem()->getType()],'users' => $users]];
    }

    public function getDataItemProg(\Application\Model\ItemProg $m_item_prog)
    {
        return ['id' => $m_item_prog->getId(),'name' => 'programming','data' => ['start_date' => $m_item_prog->getStartDate(),'item' => ['id' => $m_item_prog->getItem()->getId(),'title' => $m_item_prog->getItem()->getTitle(),'type' => $m_item_prog->getItem()->getType()]]];
    }

    public function getDataUserContact($user = null)
    {
        $ret = $this->getServiceContact()->getListId($user);
        
        if(null === $user) {
            $user = $this->getServiceUser()->getIdentity()['id'];
        }
        $ret[] = $user;
        
        return $ret;
    }

    public function getDataUserByCourse($course)
    {
        $res_user = $this->getServiceUser()->getListUserBycourse($course);
        
        $users = [];
        foreach ($res_user as $m_user) {
            $users[] = $m_user->getId();
        }
        
        return $users;
    }

    public function getDataUserByItemProg($item_prog)
    {
        $res_user = $this->getServiceUser()->getListByItemProg($item_prog);
        
        $users = [];
        foreach ($res_user as $m_user) {
            $users[] = $m_user->getId();
        }
        
        return $users;
    }

    public function getDataThreadMessage(\Application\Model\ThreadMessage $m_thread_message)
    {
        $m_thread = $this->getServiceThread()->get($m_thread_message->getThread()
            ->getId());
        
        return ['id' => $m_thread_message->getId(),'name' => 'thread.message','data' => ['message' => $m_thread_message->getMessage(),'thread' => $this->getDataThread($m_thread)]];
    }

    public function getDataThread(\Application\Model\Thread $m_thread)
    {
        return ['id' => $m_thread->getId(),'name' => 'thread','data' => ['id' => $m_thread->getId(),'title' => $m_thread->getTitle(),'course' => ['id' => $m_thread->getCourse()->getId(),'title' => $m_thread->getCourse()->getTitle()]]];
    }

    public function getDataFeed($feed)
    {
        $m_feed = $this->getServiceFeed()->get($feed);
        
        return ['id' => $feed,'name' => 'feed','data' => ['content' => $m_feed->getContent(),'picture' => $m_feed->getPicture(),'name_picture' => $m_feed->getNamePicture(),'document' => $m_feed->getDocument(),'name_document' => $m_feed->getNameDocument(),'link' => $m_feed->getLink()]];
    }

    public function getDataEvent($event)
    {
        $m_event = $this->get($event);
    
        return [
            'id' => $event,
            'name' => 'event',
            'data' => $m_event->toArray()
        ];
    }
    
    
    
    public function getDataAssignmentComment(\Application\Model\ItemAssignment $m_item_assignment, \Application\Model\ItemAssignmentComment $m_comment)
    {
        return [
            'id' => $m_item_assignment->getId(),
            'name' => 'assignment',
            'data' => [
                'item_prog' => [
                    'id' => $m_item_assignment->getItemProg()->getId(),
                ],
                'item' => [
                    'title' => $m_item_assignment->getItemProg()->getItem()->getTitle(),
                    'type' => $m_item_assignment->getItemProg()->getItem()->getType()
                ],
                'module' => [
                    'title' => $m_item_assignment->getItemProg()->getItem()->getModule()->getTitle(),
                    'course' => [
                        'id' => $m_item_assignment->getItemProg()->getItem()->getCourse()->getId(),
                        'title' => $m_item_assignment->getItemProg()->getItem()->getCourse()->getTitle()
                    ]
                ],
                'comment' => [
                    'id' => $m_comment->getId(),
                    'text' => $m_comment->getText()
                ]
            ]
        ];
    }

    public function getDataAssignmentGrade(\Application\Model\ItemAssignment $m_item_assignment)
    {
        return [
            'id' => $m_item_assignment->getId(),
            'name' => 'assignment',
            'data' => [
                'item_prog' => [
                    'id' =>  $m_item_assignment->getItemProg()->getId()
                ],
                'item' => 
                    ['title' => $m_item_assignment->getItemProg()->getItem()->getTitle(),
                     'type' => $m_item_assignment->getItemProg()->getItem()->getType()],
                     'module' => [
                         'title' => $m_item_assignment->getItemProg()->getItem()->getModule()->getTitle(),
                         'course' => [
                             'id' => $m_item_assignment->getItemProg()->getItem()->getCourse()->getId(),
                             'title' => $m_item_assignment->getItemProg()->getItem()->getCourse()->getTitle()
                         ]
                     ]
                ]
        ];
    }

    public function getDataAssignment(\Application\Model\ItemAssignment $m_item_assignment)
    {
        $users = [];
        foreach ($m_item_assignment->getStudents() as $student) {
            $users[] = ['id' => $student->getId(),'name' => 'user','data' => ['firstname' => $student->getFirstname(),'lastname' => $student->getLastname(),'avatar' => $student->getAvatar(),'school' => ['id' => $student->getSchool()->getId(),'short_name' => $student->getSchool()->getShortName(),'logo' => $student->getSchool()->getLogo()],'user_roles' => $student->getRoles()]];
        }
        
        return ['id' => $m_item_assignment->getId(),'name' => 'assignment','data' => ['users' => $users,'item' => ['title' => $m_item_assignment->getItemProg()
            ->getItem()
            ->getTitle(),'type' => $m_item_assignment->getItemProg()
            ->getItem()
            ->getType()],'item_prog' => ['id' => $m_item_assignment->getItemProg()->getId()],'module' => ['title' => $m_item_assignment->getItemProg()
            ->getItem()
            ->getModule()
            ->getTitle(),'course' => ['id' => $m_item_assignment->getItemProg()
            ->getItem()
            ->getCourse()
            ->getId(),'title' => $m_item_assignment->getItemProg()
            ->getItem()
            ->getCourse()
            ->getTitle()]]]];
    }

    public function getDataUser($id = null)
    {
        if (null == $id) {
            $id = $this->getServiceUser()->getIdentity()['id'];
        }
        
        $m_user = $this->getServiceUser()->get($id);
        return ['id' => $id,'name' => 'user','data' => ['firstname' => $m_user['firstname'],'lastname' => $m_user['lastname'],'avatar' => $m_user['avatar'],'school' => ['id' => $m_user['school']['id'],'short_name' => $m_user['school']['short_name'],'logo' => $m_user['school']['logo']],'user_roles' => $m_user['roles']]];
    }

    /**
     *
     * @return \Application\Service\ThreadMessage
     */
    public function getServiceThreadMessage()
    {
        return $this->getServiceLocator()->get('app_service_thread_message');
    }

    /**
     *
     * @return \Application\Service\MaterialDocument
     */
    public function getServiceMaterialDocument()
    {
        return $this->getServiceLocator()->get('app_service_material_document');
    }

    /**
     *
     * @return \Application\Service\Thread
     */
    public function getServiceThread()
    {
        return $this->getServiceLocator()->get('app_service_thread');
    }

    /**
     *
     * @return \Application\Service\EventUser
     */
    public function getServiceEventUser()
    {
        return $this->getServiceLocator()->get('app_service_event_user');
    }

    /**
     *
     * @return \Application\Service\User
     */
    public function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }

    /**
     *
     * @return \Application\Service\Feed
     */
    public function getServiceFeed()
    {
        return $this->serviceLocator->get('app_service_feed');
    }

    /**
     *
     * @return \Application\Service\ItemAssignment
     */
    public function getServiceItemAssignment()
    {
        return $this->getServiceLocator()->get('app_service_item_assignment');
    }

    /**
     *
     * @return \Application\Service\VideoconfArchive
     */
    public function getServiceVideoconfArchive()
    {
        return $this->getServiceLocator()->get('app_service_videoconf_archive');
    }

    /**
     *
     * @return \Application\Service\ItemProg
     */
    public function getServiceItemProg()
    {
        return $this->getServiceLocator()->get('app_service_item_prog');
    }

    /**
     *
     * @return \Application\Service\Course
     */
    public function getServiceCourse()
    {
        return $this->getServiceLocator()->get('app_service_course');
    }

    /**
     *
     * @return \Application\Service\Resume
     */
    public function getServiceResume()
    {
        return $this->getServiceLocator()->get('app_service_resume');
    }

    /**
     *
     * @return \Application\Service\School
     */
    public function getServiceSchool()
    {
        return $this->getServiceLocator()->get('app_service_school');
    }

    /**
     *
     * @return \Application\Service\Contact
     */
    public function getServiceContact()
    {
        return $this->getServiceLocator()->get('app_service_contact');
    }

    /**
     *
     * @return \Application\Service\ItemAssignmentComment
     */
    public function getServiceItemAssignmentComment()
    {
        return $this->getServiceLocator()->get('app_service_item_assignment_comment');
    }
}