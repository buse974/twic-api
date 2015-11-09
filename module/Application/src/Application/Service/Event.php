<?php

namespace Application\Service;

use Dal\Service\AbstractService;
use Zend\Json\Server\Request;
use Zend\Http\Client;

class Event extends AbstractService
{
    private static $id = 0;

    const TARGET_TYPE_USER = 'user';

    const TARGET_TYPE_GLOBAL = 'global';

    const TARGET_TYPE_SCHOOL = 'school';

    /**
     * create event.
     *
     * @param string $event
     * @param mixed  $source
     * @param mixed  $object
     * @param array  $user
     *
     * @throws \Exception
     *
     * @return int
     */
    public function create($event, $source, $object, $user, $target, $src = null)
    {
        if (!is_array($user)) {
            $user = [$user];
        }
        $user = array_values(array_unique($user));

        $date = (new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s');
        $m_event = $this->getModel()
            ->setUserId($src)
            ->setEvent($event)
            ->setSource(json_encode($source))
            ->setObject(json_encode($object))
            ->setTarget($target)
            ->setDate($date);

        if ($this->getMapper()->insert($m_event) <= 0) {
            throw new \Exception('error insert event');
        }

        $this->getServiceConnection()->add();
        $event_id = $this->getMapper()->getLastInsertValue();
        $this->getServiceEventUser()->add($user, $event_id);

        $this->sendRequest(array_values($user), array('id' => $event_id, 'event' => $event, 'source' => $source, 'date' => (new \DateTime($date))->format('Y-m-d\TH:i:s\Z'), 'object' => $object), $target);

        return $event_id;
    }

    public function sendRequest($users, $notification, $target)
    {
        $rep = false;
        $request = new Request();
        $request->setMethod('notification.publish')
            ->setParams(array('notification' => $notification, 'users' => $users, 'type' => $target))
            ->setId(++self::$id)
            ->setVersion('2.0');

        $client = new \Zend\Json\Server\Client($this->serviceLocator->get('config')['node']['addr'], $this->getClient());
        try {
            $rep = $client->doRequest($request);
            if ($rep->isError()) {
                throw new \Exception('Error jrpc nodeJs: '.$rep->getError()->getMessage(), $rep->getError()->getCode());
            }
        } catch (\Exception $e) {
            syslog(1, 'Request: '.$request->toJson());
            syslog(1, $e->getMessage());
        }

        return $rep;
    }

    public function isConnected($user)
    {
        $rep = false;
        $request = new Request();
        $request->setMethod('user.isConnected')
            ->setParams(array('user' => (int) $user))
            ->setId(++self::$id)
            ->setVersion('2.0');

        $client = new \Zend\Json\Server\Client($this->serviceLocator->get('config')['node']['addr'], $this->getClient());

        try {
            $rep = $client->doRequest($request)->getResult();
        } catch (\Exception $e) {
            syslog(1, $e->getMessage());
        }

        return $rep;
    }

    /**
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
     *
     * @param string $filter
     * @param string $events
     * @param string $user
     * @param int    $id
     * @param int    $source
     */
    public function getList($filter = null, $events = null, $user = null, $id = null, $source = null)
    {
        $mapper = $this->getMapper();
        if (null === $user) {
            $user = $this->getServiceUser()->getIdentity()['id'];
        }

        $res_event = $mapper->usePaginator($filter)->getList($user, $events, $id, $source);
        $count = $mapper->count();

        $ar_event = $res_event->toArray();
        foreach ($ar_event as &$event) {
            // $event['nb_like'] = $this->getMapper()->nbrLike($event['id']);
            $event['source'] = json_decode($event['source'], true);
            $event['object'] = json_decode($event['object'], true);
            $event['comment'] = $this->getServiceEventComment()->getList($event['id'])->toArray();
        }

        return ['list' => $ar_event,'count' => $count];
    }

    /**
     * @param int $id
     *
     * @return \Application\Model\Event
     */
    public function get($id)
    {
        $user = $this->getServiceUser()->getIdentity()['id'];
        $m_event = $this->getMapper()
            ->getList($user, null, $id)
            ->current();

        return $m_event;
    }

    // event
    public function messageNew($message, $to)
    {
        $from = $this->getDataUser();

        $ret = $this->create('message.new', $from, $this->getDataMessage($message), $to, self::TARGET_TYPE_USER, $this->getServiceUser()->getIdentity()['id']);

        foreach ($to as $t) {
            $u = $this->getDataUser($t);
            if (/*!$this->isConnected($t)*/ $u['data']['has_email_notifier'] == true) {
                try {
                    $this->getServiceMail()->sendTpl('tpl_newmessage', $u['data']['email'], array('to_firstname' => $u['data']['firstname'], 'to_lastname' => $u['data']['lastname'], 'to_avatar' => $u['data']['avatar'], 'from_firstname' => $from['data']['firstname'], 'from_lastname' => $from['data']['lastname'], 'from_avatar' => $from['data']['avatar']));
                } catch (\Exception $e) {
                    syslog(1, 'Model tpl_newmessage does not exist');
                }
            }
        }

        return $ret;
    }

    public function userPublication($feed)
    {
        return $this->create('user.publication', $this->getDataUser(), $this->getDataFeed($feed), $this->getDataUserContact(), self::TARGET_TYPE_USER, $this->getServiceUser()
            ->getIdentity()['id']);
    }
    
    public function userAnnouncement($feed)
    {
        return $this->create('user.announcement', $this->getDataUser(), $this->getDataFeed($feed), $this->getDataUser, self::TARGET_TYPE_USER, $this->getServiceUser()
            ->getIdentity()['id']);
    }
    
    public function userLike($event)
    {
        return $this->create('user.like', $this->getDataUser(), $this->getDataEvent($event), $this->getDataUserContact(), self::TARGET_TYPE_USER, $this->getServiceUser()
            ->getIdentity()['id']);
    }
    
    public function userComment($m_comment)
    {
        return $this->create('user.comment', $this->getDataUser(), $this->getDataEvent($m_comment->getEventId()), $this->getDataUserContact(), self::TARGET_TYPE_USER, $this->getServiceUser()
            ->getIdentity()['id']);
    }

    public function userAddConnection($user, $contact)
    {
        return $this->create('user.addconnection', $this->getDataUser($user), $this->getDataUser($contact), array_merge($this->getDataUserContact($contact), $this->getDataUserContact($user)), self::TARGET_TYPE_USER);
    }

    public function userDeleteConnection($user, $contact)
    {
        return $this->create('user.deleteConnection', $this->getDataUser($user), $this->getDataUser($contact), array_merge($this->getDataUserContact($contact), [$contact,$user]), self::TARGET_TYPE_USER);
    }
    
    public function studentSubmitAssignment($item_assignment)
    {
        $m_item_assignment = $this->getServiceItemAssignment()->_get($item_assignment);

        return $this->create('student.submit.assignment', $this->getDataUser(), $this->getDataAssignment($m_item_assignment), $this->getDataUserByCourseWithInstructorAndAcademic($m_item_assignment->getItemProg()
            ->getItem()
            ->getCourse()
            ->getId()), self::TARGET_TYPE_USER, $this->getServiceUser()
            ->getIdentity()['id']);
    }

    public function assignmentGraded($item_assignment, $user)
    {
        $m_item_assignment = $this->getServiceItemAssignment()->_get($item_assignment);

        $users = $this->getDataUserByCourseWithInstructorAndAcademic($m_item_assignment->getItemProg()
            ->getItem()
            ->getCourse()
            ->getId());

        $users = array_merge($user, $users);

        return $this->create('assignment.graded', $this->getDataUser(), $this->getDataAssignmentGrade($m_item_assignment), $users, self::TARGET_TYPE_USER, $this->getServiceUser()
            ->getIdentity()['id']);
    }

    public function assignmentCommented($item_assignment, $item_assignment_comment)
    {
        $m_item_assignment = $this->getServiceItemAssignment()->_get($item_assignment);
        $m_assignment_comment = $this->getServiceItemAssignmentComment()->get($item_assignment_comment);

        $res_user = $m_item_assignment->getStudents();
        $users = [];
        foreach ($res_user as $m_user) {
            $users[] = $m_user->getId();
        }

        $uai = $this->getDataUserByCourseWithInstructorAndAcademic($m_item_assignment->getItemProg()
            ->getItem()
            ->getCourse()
            ->getId());

        return $this->create('assignment.commented', $this->getDataUser(), $this->getDataAssignmentComment($m_item_assignment, $m_assignment_comment), array_merge($users, $uai), self::TARGET_TYPE_USER, $this->getServiceUser()
            ->getIdentity()['id']);
    }

    public function threadNew($thread)
    {
        $m_thread = $this->getServiceThread()->get($thread);

        return $this->create('thread.new', $this->getDataUser(), $this->getDataThread($m_thread), $this->getDataUserByCourse($m_thread->getCourse()
            ->getId()), self::TARGET_TYPE_USER, $this->getServiceUser()
            ->getIdentity()['id']);
    }

    public function threadMessage($thread_message)
    {
        $m_thread_message = $this->getServiceThreadMessage()->get($thread_message);

        return $this->create('thread.message', $this->getDataUser(), $this->getDataThreadMessage($m_thread_message), $this->getDataUserByCourse($m_thread_message->getThread()
            ->getCourseId()), self::TARGET_TYPE_USER, $this->getServiceUser()
            ->getIdentity()['id']);
    }

    public function recordAvailable($item_prog, $videoconf_archive)
    {
        $m_item_prog = $this->getServiceItemProg()->get($item_prog);
        $m_videoconf_archive = $this->getServiceVideoconfArchive()->get($videoconf_archive);

        return $this->create('record.available', $this->getDataItemProg($m_item_prog), $this->getDataVideoArchive($m_videoconf_archive), $this->getListByItemProgWithInstrutorAndAcademic($m_item_prog->getId()), self::TARGET_TYPE_USER);
    }

    public function eqcqAvailable($item_prog)
    {
        $m_item_prog = $this->getServiceItemProg()->get($item_prog);

        return $this->create('eqcq.available', $this->getDataItemProgWihtUser($m_item_prog), [], $this->getListByItemProgWithInstrutorAndAcademic($m_item_prog->getId()), self::TARGET_TYPE_USER);
    }

    public function courseUpdated($course, $dataupdated)
    {
        return $this->create('course.updated', $this->getDataUser(), $this->getDataCourseUpdate($course, $dataupdated), $this->getDataUserByCourseWithStudentAndInstructorAndAcademic($course), self::TARGET_TYPE_USER, $this->getServiceUser()
            ->getIdentity()['id']);
    }

    public function courseParticipation($course, $dataupdated)
    {
        return $this->create('course.participation', $this->getDataUser(), $this->getDataCourseUpdate($course, $dataupdated), $this->getDataUserByCourse($course), self::TARGET_TYPE_USER, $this->getServiceUser()
            ->getIdentity()['id']);
    }

    public function courseMaterialAdded($course, $material)
    {
        return $this->create('course.material_added', $this->getDataUser(), $this->getDataCourseAddMaterial($course, $material), $this->getDataUserByCourseWithStudentAndInstructorAndAcademic($course), self::TARGET_TYPE_USER, $this->getServiceUser()
            ->getIdentity()['id']);
    }

    public function programmationNew($item_prog)
    {
        return $this->create('programmation.new', $this->getDataUser(), $this->getDataProgrammation($item_prog), $this->getListByItemProgWithInstrutorAndAcademic($item_prog), self::TARGET_TYPE_USER, $this->getServiceUser()
            ->getIdentity()['id']);
    }

    public function programmationUpdated($item_prog)
    {
        return $this->create('programmation.updated', $this->getDataUser(), $this->getDataProgrammation($item_prog), $this->getListByItemProgWithInstrutorAndAcademic($item_prog), self::TARGET_TYPE_USER, $this->getServiceUser()
            ->getIdentity()['id']);
    }

    public function profileUpdated($user, $dataprofile)
    {
        return $this->create('profile.updated', $this->getDataUser(), $this->getDataUpdateProfile($user, $dataprofile), $this->getDataUserContact(), self::TARGET_TYPE_USER, $this->getServiceUser()
            ->getIdentity()['id']);
    }

    public function profileNewresume($resume)
    {
        return $this->create('profile.newresume', $this->getDataUser(), $this->getDataResume($resume), $this->getDataUserContact(), self::TARGET_TYPE_USER, $this->getServiceUser()
            ->getIdentity()['id']);
    }

    public function userRequestconnection($user)
    {
        $u = $this->getDataUser();
        $uu = $this->getDataUser($user);

        try {
            $this->getServiceMail()->sendTpl('tpl_newrequest', $uu['data']['email'], array('firstname' => $u['data']['firstname'], 'lastname' => $u['data']['lastname'], 'avatar' => $u['data']['avatar'], 'school_name' => $u['data']['school']['short_name'], 'school_logo' => $u['data']['school']['logo']));
        } catch (\Exception $e) {
            syslog(1, 'Model tpl_newrequest does not exist');
        }

        return $this->create('user.requestconnection', $u, $uu, [$user], self::TARGET_TYPE_USER, $this->getServiceUser()
            ->getIdentity()['id']);
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
            ->getTitle(), ]]]];
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

        return ['id' => $course,'name' => 'course','data' => [
            'title' => $m_course->getTitle(),
            'picture' => $m_course->getPicture(),
            'program' => $m_course->getProgram()->getId(),
            'updated' => array_keys($dataupdated)]];
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

        if (null === $user) {
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

    public function getDataUserByCourseWithStudentAndInstructorAndAcademic($course)
    {
        $res_user = $this->getServiceUser()->getListUserBycourseWithStudentAndInstructorAndAcademic($course);

        $users = [];
        foreach ($res_user as $m_user) {
            $users[] = $m_user->getId();
        }

        return $users;
    }

    public function getDataUserByCourseWithInstructorAndAcademic($course)
    {
        $res_user = $this->getServiceUser()->getListUserBycourseWithInstructorAndAcademic($course);

        $users = [];
        foreach ($res_user as $m_user) {
            $users[] = $m_user->getId();
        }

        return $users;
    }

    public function getListByItemProgWithInstrutorAndAcademic($item_prog)
    {
        $res_user = $this->getServiceUser()->getListByItemProgWithInstrutorAndAcademic($item_prog);

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

        return ['id' => $m_thread_message->getId(),'name' => 'thread.message','data' => ['message' => $m_thread_message->getMessage(),'thread' => $this->getDataThread($m_thread)['data']]];
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

        return ['id' => $event,'name' => 'event','data' => $m_event->toArray()];
    }
    
    public function getDataEventComment($m_event_comment)
    {
        return ['id' => $m_event_comment->getId(),'name' => 'comment','data' => $m_event_comment->toArray()];
    }

    public function getDataAssignmentComment(\Application\Model\ItemAssignment $m_item_assignment, \Application\Model\ItemAssignmentComment $m_comment)
    {
        return ['id' => $m_item_assignment->getId(),'name' => 'assignment','data' => ['item_prog' => ['id' => $m_item_assignment->getItemProg()->getId()],'item' => ['title' => $m_item_assignment->getItemProg()
            ->getItem()
            ->getTitle(),'type' => $m_item_assignment->getItemProg()
            ->getItem()
            ->getType(), ],'module' => ['title' => $m_item_assignment->getItemProg()
            ->getItem()
            ->getModule()
            ->getTitle(),'course' => ['id' => $m_item_assignment->getItemProg()
            ->getItem()
            ->getCourse()
            ->getId(),'title' => $m_item_assignment->getItemProg()
            ->getItem()
            ->getCourse()
            ->getTitle(), ], ],'comment' => ['id' => $m_comment->getId(),'text' => $m_comment->getText()]]];
    }

    public function getDataAssignmentGrade(\Application\Model\ItemAssignment $m_item_assignment)
    {
        return ['id' => $m_item_assignment->getId(),'name' => 'assignment','data' => ['item_prog' => ['id' => $m_item_assignment->getItemProg()->getId()],'item' => ['title' => $m_item_assignment->getItemProg()
            ->getItem()
            ->getTitle(),'type' => $m_item_assignment->getItemProg()
            ->getItem()
            ->getType(), ],'module' => ['title' => $m_item_assignment->getItemProg()
            ->getItem()
            ->getModule()
            ->getTitle(),'course' => ['id' => $m_item_assignment->getItemProg()
            ->getItem()
            ->getCourse()
            ->getId(),'title' => $m_item_assignment->getItemProg()
            ->getItem()
            ->getCourse()
            ->getTitle(), ], ]]];
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
            ->getType(), ],'item_prog' => ['id' => $m_item_assignment->getItemProg()->getId()],'module' => ['title' => $m_item_assignment->getItemProg()
            ->getItem()
            ->getModule()
            ->getTitle(),'course' => ['id' => $m_item_assignment->getItemProg()
            ->getItem()
            ->getCourse()
            ->getId(),'title' => $m_item_assignment->getItemProg()
            ->getItem()
            ->getCourse()
            ->getTitle(), ], ]]];
    }

    public function getDataUser($id = null)
    {
        if (null == $id) {
            $id = $this->getServiceUser()->getIdentity()['id'];
        }

        $m_user = $this->getServiceUser()->get($id);

        return ['id' => $id,'name' => 'user','data' => ['firstname' => $m_user['firstname'],'email' => $m_user['email'],'lastname' => $m_user['lastname'],'has_email_notifier' => $m_user['has_email_notifier'],'avatar' => $m_user['avatar'],'school' => ['id' => $m_user['school']['id'],'short_name' => $m_user['school']['short_name'],'logo' => $m_user['school']['logo']],'user_roles' => $m_user['roles']]];
    }

    public function getDataMessage($message)
    {
        $m_message = $this->getServiceMessageUser()
            ->getMessage($message)
            ->getMessage();

        return ['id' => $m_message->getId(),'name' => 'message','data' => $m_message];
    }

    /**
     * @return \Application\Service\ThreadMessage
     */
    public function getServiceThreadMessage()
    {
        return $this->getServiceLocator()->get('app_service_thread_message');
    }

    /**
     * @return \Application\Service\MaterialDocument
     */
    public function getServiceMaterialDocument()
    {
        return $this->getServiceLocator()->get('app_service_material_document');
    }

    /**
     * @return \Application\Service\Thread
     */
    public function getServiceThread()
    {
        return $this->getServiceLocator()->get('app_service_thread');
    }

    /**
     * @return \Application\Service\EventUser
     */
    public function getServiceEventUser()
    {
        return $this->getServiceLocator()->get('app_service_event_user');
    }

    /**
     * @return \Application\Service\EventComment
     */
    public function getServiceEventComment()
    {
        return $this->getServiceLocator()->get('app_service_event_comment');
    }

    /**
     * @return \Application\Service\User
     */
    public function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }

    /**
     * @return \Application\Service\Feed
     */
    public function getServiceFeed()
    {
        return $this->serviceLocator->get('app_service_feed');
    }

    /**
     * @return \Application\Service\ItemAssignment
     */
    public function getServiceItemAssignment()
    {
        return $this->getServiceLocator()->get('app_service_item_assignment');
    }

    /**
     * @return \Application\Service\VideoconfArchive
     */
    public function getServiceVideoconfArchive()
    {
        return $this->getServiceLocator()->get('app_service_videoconf_archive');
    }

    /**
     * @return \Application\Service\ItemProg
     */
    public function getServiceItemProg()
    {
        return $this->getServiceLocator()->get('app_service_item_prog');
    }

    /**
     * @return \Application\Service\Course
     */
    public function getServiceCourse()
    {
        return $this->getServiceLocator()->get('app_service_course');
    }

    /**
     * @return \Application\Service\Resume
     */
    public function getServiceResume()
    {
        return $this->getServiceLocator()->get('app_service_resume');
    }

    /**
     * @return \Application\Service\School
     */
    public function getServiceSchool()
    {
        return $this->getServiceLocator()->get('app_service_school');
    }

    /**
     * @return \Application\Service\Contact
     */
    public function getServiceContact()
    {
        return $this->getServiceLocator()->get('app_service_contact');
    }

    /**
     * @return \Application\Service\ItemAssignmentComment
     */
    public function getServiceItemAssignmentComment()
    {
        return $this->getServiceLocator()->get('app_service_item_assignment_comment');
    }

    /**
     * @return \Mail\Service\Mail
     */
    public function getServiceMail()
    {
        return $this->getServiceLocator()->get('mail.service');
    }

    /**
     * @return \Application\Service\MessageUser
     */
    public function getServiceMessageUser()
    {
        return $this->getServiceLocator()->get('app_service_message_user');
    }

    /**
     * @return \Application\Service\Message
     */
    public function getServiceMessage()
    {
        return $this->getServiceLocator()->get('app_service_message');
    }
    
    /**
     * @return \Application\Service\Connection
     */
    public function getServiceConnection()
    {
        return $this->getServiceLocator()->get('app_service_connection');
    }
}
