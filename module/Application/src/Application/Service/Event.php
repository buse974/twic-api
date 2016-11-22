<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Event
 */
namespace Application\Service;

use Dal\Service\AbstractService;
use Zend\Json\Server\Request;
use Zend\Http\Client;

/**
 * Class Event
 */
class Event extends AbstractService
{

    /**
     * Identification request.
     *
     * @var int
     */
    private static $id = 0;

    const TARGET_TYPE_USER = 'user';
    const TARGET_TYPE_GLOBAL = 'global';
    const TARGET_TYPE_SCHOOL = 'school';

    /**
     * create event
     *
     * @param string $event
     * @param mixed $source
     * @param mixed $object
     * @param array $user
     * @param mixed $target
     * @param mixed $src
     * @throws \Exception
     * @return int
     */
    public function create($event, $source, $object, $libelle, $target, $src = null)
    {
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
        $event_id = $this->getMapper()->getLastInsertValue();
        $this->getServiceEventSubscription()->add($libelle, $event_id);
        $user = $this->getServiceSubscription()->getListUserId($libelle);
        if(count($user) > 0){
            $this->sendRequest(array_values($user), [
                'id' => $event_id,
                'event' => $event,
                'source' => $source,
                'date' => (new \DateTime($date))->format('Y-m-d\TH:i:s\Z'),
                'object' => $object]
                , $target);
        }
        
        return $event_id;
    }

    /**
     * Send Request Event.
     *
     * @param array $users            
     * @param array $notification            
     * @param mixed $target            
     *
     * @throws \Exception
     *
     * @return \Zend\Json\Server\Response
     */
    public function sendRequest($users, $notification, $target)
    {
        $rep = false;
        $request = new Request();
        $request->setMethod('notification.publish')
            ->setParams(array('notification' => $notification,'users' => $users,'type' => $target))
            ->setId(++ self::$id)
            ->setVersion('2.0');
        
        $client = new \Zend\Json\Server\Client($this->container->get('config')['node']['addr'], $this->getClient());
        try {
            $rep = $client->doRequest($request);
            if ($rep->isError()) {
                throw new \Exception('Error jrpc nodeJs: ' . $rep->getError()->getMessage(), $rep->getError()->getCode());
            }
        } catch (\Exception $e) {
            syslog(1, 'Request: ' . $request->toJson());
            syslog(1, $e->getMessage());
        }
        
        return $rep;
    }

    /**
     * Check if User is connect.
     *
     * @param int $user            
     *
     * @return array
     */
    public function isConnected($user)
    {
        $rep = false;
        $request = new Request();
        $request->setMethod('user.isConnected')
            ->setParams(array('user' => (int) $user))
            ->setId(++ self::$id)
            ->setVersion('2.0');
        
        $client = new \Zend\Json\Server\Client($this->container->get('config')['node']['addr'], $this->getClient());
        
        try {
            $rep = $client->doRequest($request)->getResult();
        } catch (\Exception $e) {
            syslog(1, $e->getMessage());
        }
        
        return $rep;
    }

    /**
     * Get Client Http.
     *
     * @return \Zend\Http\Client
     */
    private function getClient()
    {
        $client = new Client();
        $client->setOptions($this->container->get('config')['http-adapter']);
        
        return $client;
    }

    /**
     * Get List Event.
     *
     * @invokable
     *
     * @param array $filter            
     * @param string $events            
     * @param int $user            
     * @param int $id            
     * @param int $source            
     *
     * @return array
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
            $event['source'] = json_decode($event['source'], true);
            $event['object'] = json_decode($event['object'], true);;
        }
        
        return ['list' => $ar_event,'count' => $count];
    }

    /**
     * Get Event.
     *
     * @param int $id            
     *
     * @return \Application\Model\Event
     */
    public function get($id)
    {
        $m_event = $this->getMapper()
            ->getList($this->getServiceUser()
            ->getIdentity()['id'], null, $id)
            ->current();
        
        return $m_event;
    }
    
    
    // /////////////// EVENT //////////////////////
    
    /**
     * Event user.publication
     * 
     * @param int $post_id
     * @param array $sub
     * @return number
     */
    public function userPublication($sub, $post_id, $type = null, $ev = null)
    {
        $user_id = $this->getServiceUser()->getIdentity()['id'];
        $data_post = $this->getDataPost($post_id);
        
        if (null === $type) {
            'user.publication';
        } else {
            $event = $type;
            if(null !== $ev) {
                $event = '.'.$ev;
            }
        }
        
        return $this->create($event, $this->getDataUser(), $data_post, $sub, self::TARGET_TYPE_USER, $user_id);
    }
    
    /**
     * Event user.like
     *
     * @param array $sub
     * @param int $post_id
     */
    public function userLike($sub, $post_id)
    {
        $user_id = $this->getServiceUser()->getIdentity()['id'];
    
        return $this->create('user.like', $this->getDataUser(), $this->getDataPost($post_id), $sub, self::TARGET_TYPE_USER, $user_id);
    
    }
    
    /**
     * Event profile.updated
     *
     * @param array $sub
     * @param int $user_id
     * @param array $dataprofile
     * @return int
     */
    public function profileUpdated($sub, $user_id, $dataprofile)
    {
        return $this->create('profile.updated', $this->getDataUser(), $this->getDataUpdateProfile($user_id, $dataprofile), $sub, self::TARGET_TYPE_USER, $user_id);
    }
    
    /**
     * Event profile.newresume.
     *
     * @param inr $sub
     * @param inr $resume
     * @return int
     */
    public function profileNewresume($sub, $resume)
    {
        $user_id = $this->getServiceUser()->getIdentity()['id'];
    
        return $this->create('profile.newresume', $this->getDataUser(), $this->getDataResume($resume), $sub, self::TARGET_TYPE_USER, $user_id);
    }
    
    /**
     * Event page.new
     *
     * @param array $sub
     * @param int $page
     */
    public function pageNew($sub, $page)
    {
        $user_id = $this->getServiceUser()->getIdentity()['id'];
    
        return $this->create('page.new', $this->getDataUser(), $this->getDataPage($page), $sub, self::TARGET_TYPE_USER, $user_id);
    }
    
    /**
     * Event pageuser.invited
     *
     * @param array $sub
     * @param int $page
     */
    public function pageUserInvited($sub, $page)
    {
        $user_id = $this->getServiceUser()->getIdentity()['id'];
    
        return $this->create('pageuser.invited', $this->getDataUser(), $this->getDataPage($page), $sub, self::TARGET_TYPE_USER, $user_id);
    }
    
    /**
     * Event pageuser.member
     *
     * @param array $sub
     * @param int $page
     */
    public function pageUserMember($sub, $page)
    {
        $user_id = $this->getServiceUser()->getIdentity()['id'];
    
        return $this->create('pageuser.member', $this->getDataUser(), $this->getDataPage($page), $sub, self::TARGET_TYPE_USER, $user_id);
    }
        
    /**
     * Event message.new
     *
     * @param int $message_id            
     * @param array $to            
     *
     * @return int
     */
    public function messageNew($message_id, $to)
    {
        if(!is_array($to)) {
            $to = [$to];
        }
        foreach ($to as $tt) {
            $ttto[] = 'SU'.$tt;
        }
        $from = $this->getDataUser();
        $ret = $this->create('message.new', $from, $this->getDataMessage($message_id), $ttto, self::TARGET_TYPE_USER, $this->getServiceUser()->getIdentity()['id']);
        
        foreach ($to as $t) {
            $u = $this->getDataUser($t);
            //if (!$this->isConnected($t)  $u['data']['has_email_notifier'] == true) {
            if ( $u['data']['has_email_notifier'] == true) {
                try {
                    $this->getServiceMail()->sendTpl('tpl_newmessage', $u['data']['email'], array('to_firstname' => $u['data']['firstname'],'to_lastname' => $u['data']['lastname'],'to_avatar' => $u['data']['avatar'],'from_firstname' => $from['data']['firstname'],'from_lastname' => $from['data']['lastname'],'from_avatar' => $from['data']['avatar']));
                } catch (\Exception $e) {
                    syslog(1, 'Model tpl_newmessage does not exist');
                }
            }
        }
        
        return $ret;
    }

    /**
     * Event user.announcement
     *
     * @param int $feed_id            
     *
     * @return int
     */
    public function userAnnouncement($feed_id)
    {
        /*$user_id = $this->getServiceUser()->getIdentity()['id'];
        
        return $this->create('user.announcement', $this->getDataUser(), $this->getDataFeed($feed_id), 'U'.$user_id, self::TARGET_TYPE_USER, $user_id);
        */
    }

    /**
     * Event task.shared.
     *
     * @param int $task_id            
     * @param array $users            
     *
     * @return int
     */
    public function taskshared($task_id, $users)
    {
        //return $this->create('task.shared', $this->getDataUser(), $this->getDataTask($task_id), 'T'.$task_id, self::TARGET_TYPE_USER, $this->getServiceUser()->getIdentity()['id']);
    }

    /**
     * Event user.comment.
     *
     * @param \Application\Model\EventComment $task_id            
     *
     * @return int
     */
    public function userComment($m_comment)
    {
        //return $this->create('user.comment', $this->getDataUser(), $this->getDataEvent($m_comment->getEventId()), 'U'.$user_id, self::TARGET_TYPE_USER, $this->getServiceUser()->getIdentity()['id']);
    }

    /**
     * Event user.addconnection.
     *
     * @param int $user_id            
     * @param int $contact_id            
     *
     * @return int
     */
    public function userAddConnection($user_id, $contact_id)
    {
        //return $this->create('user.addconnection', $this->getDataUser($user_id), $this->getDataUser($contact_id),  ['U'.$contact_id, 'U'.$user_id] , self::TARGET_TYPE_USER);
    }

    /**
     * Event user.deleteconnection.
     *
     * @param int $user_id            
     * @param int $contact_id            
     *
     * @return int
     */
    public function userDeleteConnection($user_id, $contact_id)
    {
        //return $this->create('user.deleteconnection', $this->getDataUser($user_id), $this->getDataUser($contact_id),  ['U'.$contact_id, 'U'.$user_id], self::TARGET_TYPE_USER);
    }
    

    /**
     * Event submission.graded.
     *
     * @param int $submission_id            
     * @param int $user_id            
     *
     * @return int
     */
    public function submissionGraded($submission_id, $user_id)
    {
        /*
        $m_submission = $this->getServiceSubmission()->getWithItem($submission_id);
        
        $u_id = $this->getServiceUser()->getIdentity()['id'];
        $src = $this->getDataUser();
        $ret = false;
        if (! empty($src) && $u_id !== $user_id) {
            $ret = $this->create('submission.graded', $this->getDataUser(), $this->getDataSubmissionWihtUser($m_submission), 'UU'.$user_id, self::TARGET_TYPE_USER, $u_id);
        }
        
        return $ret;
        */
    }

    /**
     * Event submission.commented.
     *
     * @param int $submission_id            
     * @param int $submission_comments_id            
     *
     * @return int
     */
    public function submissionCommented($submission_id, $submission_comments_id)
    {
        /*
        $m_submission = $this->getServiceSubmission()->getWithItem($submission_id);
        $m_comment = $this->getServiceSubmissionComments()->get($submission_comments_id);
        
        return $this->create('submission.commented', $this->getDataUser(), $this->getDataSubmissionComment($m_submission, $m_comment), 'C'.$m_submission->getItem()
            ->getCourseId(), self::TARGET_TYPE_USER, $this->getServiceUser()
            ->getIdentity()['id']);
        */
    }

    /**
     * Event thread.new.
     *
     * @param int $thread_id            
     *
     * @return int
     */
    public function threadNew($thread_id)
    {
        /*
        $m_thread = $this->getServiceThread()->get($thread_id);
        
        return $this->create('thread.new', $this->getDataUser(), $this->getDataThread($m_thread), 'C'.$m_thread->getCourse()
            ->getId(), self::TARGET_TYPE_USER, $this->getServiceUser()
            ->getIdentity()['id']);
        */
    }

    /**
     * Event thread.message.
     *
     * @param int $thread_message_id            
     *
     * @return int
     */
    public function threadMessage($thread_message_id)
    {
        /*
        $m_thread_message = $this->getServiceThreadMessage()->get($thread_message_id);
        
        return $this->create('thread.message', $this->getDataUser(), $this->getDataThreadMessage($m_thread_message), 'C'.$m_thread_message->getThread()
            ->getCourseId(), self::TARGET_TYPE_USER, $this->getServiceUser()
            ->getIdentity()['id']);
        */
    }

    /**
     * Event record.available
     *
     * @param \Application\Model\VideoArchive $m_video_archive            
     * @param int $item_id            
     *
     * @return int
     */
    public function recordAvailable($m_video_archive, $item_id = null)
    {
        /*$user = $this->getDataUserByConversation($m_video_archive->getConversationId());
       
        return $this->create('record.available', $this->getDataVideoArchive($m_video_archive), (null !== $item_id) ? $this->getDataItem($item_id) : null, 'I'.$item_id, self::TARGET_TYPE_USER);
        */
    }

    /**
     * Event eqcq.available.
     *
     * @param int $submission_id            
     *
     * @return int
     */
    public function eqcqAvailable($submission_id)
    {
        /*$m_submission = $this->getServiceSubmission()->getWithItem($submission_id);
        
        return $this->create('eqcq.available', [], $this->getDataSubmissionWihtUser($m_submission), 'S'.$m_submission->getId(), self::TARGET_TYPE_USER);
        */
    }

    /**
     * Event pg.graded.
     *
     * @param int $submission_id            
     *
     * @return int
     */
    public function pgAssigned($submission_id)
    {
        /*$m_submission = $this->getServiceSubmission()->getWithItem($submission_id);
       
        return $this->create('pg.graded', $this->getDataUser(), $this->getDataSubmissionWihtUser($m_submission), $user, self::TARGET_TYPE_USER);*/
    }

    /**
     * Event submit.request.
     *
     * @param int $submission_id            
     * @param int $user_id            
     *
     * @return int
     */
    public function requestSubmit($submission_id, $user_id)
    {
        /*$m_submission = $this->getServiceSubmission()->getWithItem($submission_id);
        
        return $this->create('submit.request', $this->getDataUser(), $this->getDataSubmission($m_submission), $user_id, self::TARGET_TYPE_USER);*/
    }

    /**
     * Event submission.submit.
     *
     * @param int $submission_id            
     *
     * @return int
     */
    public function endSubmit($submission_id)
    {
        /*$m_submission = $this->getServiceSubmission()->getWithItem($submission_id);
        
        $src = $this->getDataUser();
        
        $ret = false;
        if (! empty($src)) {
            $ret = $this->create('submission.submit', $src, $this->getDataSubmission($m_submission), 'I'.$m_submission->getItemId(), self::TARGET_TYPE_USER);
        }
        
        return $ret;*/
    }

    /**
     * Event course.updated.
     *
     * @param int $course_id            
     * @param array $dataupdated            
     *
     * @return int
     */
    public function courseUpdated($course_id, $dataupdated)
    {
        //return $this->create('course.updated', $this->getDataUser(), $this->getDataCourseUpdate($course_id, $dataupdated), 'C'.$course_id, self::TARGET_TYPE_USER, $this->getServiceUser()->getIdentity()['id']);
    }

    /**
     * Event submission.new.
     *
     * @param int $submission_id            
     *
     * @return int
     */
    public function programmationNew($submission_id)
    {
        //return $this->create('submission.new', $this->getDataUser(), $this->getDataProgrammation($submission_id), 'S'.$submission_id, self::TARGET_TYPE_USER, $this->getServiceUser()->getIdentity()['id']);
    }

    /**
     * Event submission.updated.
     *
     * @param int $submission_id            
     *
     * @return int
     */
    public function programmationUpdated($submission_id)
    {
        //return $this->create('submission.updated', $this->getDataUser(), $this->getDataProgrammation($submission_id), $this->getDataUserBySubmission($submission_id), self::TARGET_TYPE_USER, $this->getServiceUser()->getIdentity()['id']);
    }

    /**
     * Event user.requestconnection.
     *
     * @param int $user_id            
     *
     * @return int
     */
    public function userRequestconnection($user_id)
    {
        /*$u = $this->getDataUser();
        $uu = $this->getDataUser($user_id);
        
        try {
            $this->getServiceMail()->sendTpl('tpl_newrequest', $uu['data']['email'], array('to_firstname' => $uu['data']['firstname'],'to_lastname' => $uu['data']['lastname'],'firstname' => $u['data']['firstname'],'lastname' => $u['data']['lastname'],'avatar' => $u['data']['avatar'],'school_name' => $u['data']['school']['short_name'],'school_logo' => $u['data']['school']['logo']));
        } catch (\Exception $e) {
            syslog(1, 'Model tpl_newrequest does not exist');
        }
        
        return $this->create('user.requestconnection', $u, $uu, 'U'.$user_id, self::TARGET_TYPE_USER, $this->getServiceUser()
            ->getIdentity()['id']);*/
    }

    /**
     * Event school.new.
     *
     * @param int $school_id            
     *
     * @return int
     */
    public function schoolNew($school_id)
    {
        return $this->create('school.new', [], $this->getDataSchool($school_id), [], self::TARGET_TYPE_GLOBAL);
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    // ------------- DATA OBJECT -------------------
    
    /**
     * Get Data Post
     *
     * @param int $post_id
     * @return array
     */
    private function getDataPost($post_id)
    {
        $m_post = $this->getServicePost()->get($post_id);

        return [
            'id' => $m_post->getId(),
            'name' => 'post',
            'data' => [
                'id' =>  $m_post->getId(),
                'content' => $m_post->getContent(),
                'picture' => $m_post->getPicture(),
                'name_picture' => $m_post->getNamePicture(),
                'link' => $m_post->getLink(),
                't_organization_id' => $m_post->getTOrganizationId(),
                't_page_id' => $m_post->getTPageId(),
                't_user_id' => $m_post->getTUserId(),
                't_course_id' => $m_post->getTCourseId(),
                'parent_id' => $m_post->getParentId(),
                'type' => $m_post->getType(),
            ]
        ];
    }
    
    
    
    
    
    
    
    
    
    
    
    /**
     * Get Data School.
     *
     * @param int $school_id            
     *
     * @return array
     */
    public function getDataSchool($school_id)
    {
        $m_school = $this->getServiceSchool()->get($school_id);
        
        return ['id' => $m_school->getId(),'name' => 'school','data' => ['id' => $m_school->getId(),'name' => $m_school->getName(),'short_name' => $m_school->getShortName(),'logo' => $m_school->getLogo()]];
    }

    /**
     * Get Data Task.
     *
     * @param int $task_id            
     *
     * @return array
     */
    private function getDataTask($task_id)
    {
        $m_task = $this->getServiceTask()->get($task_id);
        
        return ['id' => $m_task->getId(),'name' => 'task','data' => $m_task->toArray()];
    }

    /**
     * Get Data resume.
     *
     * @param int $resume_id            
     *
     * @return array
     */
    private function getDataResume($resume_id)
    {
        $m_resume = $this->getServiceResume()->getById($resume_id);
        
        return ['id' => $resume_id,'name' => 'resume','data' => ['start_date' => $m_resume->getStartDate(),'end_date' => $m_resume->getEndDate(),'address' => $m_resume->getAddress(),'title' => $m_resume->getTitle(),'subtitle' => $m_resume->getSubtitle(),'logo' => $m_resume->getLogo(),'description' => $m_resume->getDescription(),'type' => $m_resume->getType()]];
    }

    /**
     * Get Data Page
     *
     * @param int $page_id
     *
     * @return array
     */
    private function getDataPage($page_id)
    {
        $m_page = $this->getServicePage()->getLite($page_id);
    
        return ['id' => $page_id,'name' => 'page','data' => $m_page->toArray()];
    }
    
    
    /**
     * Get Data User for update profile.
     *
     * @param int $user_id            
     * @param array $dataupdated            
     *
     * @return array
     */
    private function getDataUpdateProfile($user_id, $dataupdated)
    {
        if (isset($dataupdated['id'])) {
            unset($dataupdated['id']);
        }
        
        return ['id' => $user_id,'name' => 'user','data' => ['updated' => array_keys($dataupdated)]];
    }

    /**
     * Get Data submission.
     *
     * @param int $submission_id            
     *
     * @return array
     */
    private function getDataProgrammation($submission_id)
    {
        $m_submission = $this->getServiceSubmission()->getWithItem($submission_id);
        
        return ['id' => $m_submission->getId(),'name' => 'submission','data' => ['item' => ['id' => $m_submission->getItem()->getId(),'title' => $m_submission->getItem()->getTitle(),'type' => $m_submission->getItem()->getType(),'duration' => $m_submission->getItem()->getDuration(),'start' => $m_submission->getItem()->getStart(),'cut_off' => $m_submission->getItem()->getCutOff()]]];
    }

    /**
     * Get Data Course for update.
     *
     * @param int $course_id            
     * @param array $dataupdated            
     *
     * @return array
     */
    private function getDataCourseUpdate($course_id, $dataupdated)
    {
        $m_course = $this->getServiceCourse()->get($course_id);
        
        if (isset($dataupdated['id'])) {
            unset($dataupdated['id']);
        }
        
        return ['id' => $course_id,'name' => 'course','data' => ['title' => $m_course->getTitle(),'picture' => $m_course->getPicture(),'program' => $m_course->getProgram()->getId(),'updated' => array_keys($dataupdated)]];
    }

    /**
     * Get Data Vide Archive.
     *
     * @param \Application\Model\VideoArchive $m_video_archive            
     *
     * @return array
     */
    private function getDataVideoArchive(\Application\Model\VideoArchive $m_video_archive)
    {
        return ['id' => $m_video_archive->getId(),'name' => 'archive','data' => ['archive_link' => $m_video_archive->getArchiveLink()]];
    }

    /**
     * Get Data Submission With User.
     *
     * @param \Application\Model\Submission $m_submission            
     *
     * @return array
     */
    private function getDataSubmissionWihtUser(\Application\Model\Submission $m_submission)
    {
        $res_user = $this->getServiceUser()->getListUsersBySubmission($m_submission->getId());
        
        $users = [];
        foreach ($res_user as $m_user) {
            $users[] = ['firstname' => $m_user->getFirstname(),'lastname' => $m_user->getLastname(),'avatar' => $m_user->getAvatar()];
        }
        
        return ['id' => $m_submission->getId(),'name' => 'submission','data' => ['item' => ['id' => $m_submission->getItemId(),'title' => $m_submission->getItem()->getTitle(),'type' => $m_submission->getItem()->getType()],'users' => $users]];
    }

    /**
     * Get DataSubmission.
     *
     * @param \Application\Model\Submission $m_submission            
     *
     * @return array
     */
    private function getDataSubmission(\Application\Model\Submission $m_submission)
    {
        return ['id' => $m_submission->getId(),'name' => 'submission','data' => ['item' => ['id' => $m_submission->getItem()->getId(),'title' => $m_submission->getItem()->getTitle(),'type' => $m_submission->getItem()->getType()]]];
    }

    /**
     * Get Data THread Message.
     *
     * @param \Application\Model\ThreadMessage $m_thread_message            
     *
     * @return array
     */
    private function getDataThreadMessage(\Application\Model\ThreadMessage $m_thread_message)
    {
        $m_thread = $this->getServiceThread()->get($m_thread_message->getThread()
            ->getId());
        
        return ['id' => $m_thread_message->getId(),'name' => 'thread.message','data' => ['message' => $m_thread_message->getMessage(),'thread' => $this->getDataThread($m_thread)['data']]];
    }

    /**
     * Get Data Thread.
     *
     * @param \Application\Model\Thread $m_thread            
     *
     * @return array
     */
    private function getDataThread(\Application\Model\Thread $m_thread)
    {
        return ['id' => $m_thread->getId(),'name' => 'thread','data' => ['id' => $m_thread->getId(),'title' => $m_thread->getTitle(),'course' => ['id' => $m_thread->getCourse()->getId(),'title' => $m_thread->getCourse()->getTitle()]]];
    }

    /**
     *
     * @param int $item_id            
     * @return array
     */
    private function getDataItem($item_id)
    {
        return ['id' => $item_id,'name' => 'item','data' => []];
    }

    /**
     * Get Data Event.
     *
     * @param int $event            
     *
     * @return array
     */
    private function getDataEvent($event)
    {
        $m_event = $this->get($event);
        
        return ['id' => $event,'name' => 'event','data' => $m_event->toArray()];
    }

    /**
     * Get Data Submission Comment.
     *
     * @param \Application\Model\Submission $m_submisssion            
     * @param \Application\Model\SubmissionComments $m_comment            
     *
     * @return array
     */
    private function getDataSubmissionComment(\Application\Model\Submission $m_submisssion, \Application\Model\SubmissionComments $m_comment)
    {
        return ['id' => $m_submisssion->getId(),'name' => 'submission','data' => ['item' => ['id' => $m_submisssion->getItem()->getId(),'title' => $m_submisssion->getItem()->getTitle(),'type' => $m_submisssion->getItem()->getType()],'comment' => ['id' => $m_comment->getId(),'text' => $m_comment->getText()]]];
    }

    /**
     * Get Data User.
     *
     * @param int $user_id            
     *
     * @return array
     */
    private function getDataUser($user_id = null)
    {
        if (null == $user_id) {
            $identity = $this->getServiceUser()->getIdentity();
            if ($identity === null) {
                return [];
            }
            $user_id = $identity['id'];
        }
        
        $m_user = $this->getServiceUser()->get($user_id);
        
        return ['id' => $user_id,
            'name' => 'user','data' => 
            ['firstname' => $m_user['firstname'],'email' => $m_user['email'],'lastname' => $m_user['lastname'],'nickname' => $m_user['nickname'],'gender' => 
                $m_user['gender'],'has_email_notifier' => $m_user['has_email_notifier'],'avatar' => $m_user['avatar'],'school' => 
                ['id' => $m_user['school']['id'],'short_name' => $m_user['school']['short_name'],'logo' => 
                    $m_user['school']['logo'],'background' => $m_user['school']['background'],'name' => $m_user['school']['name']],
                'user_roles' => $m_user['roles']]];
    }

    /**
     * Get Data Message.
     *
     * @param int $message_id            
     *
     * @return array
     */
    private function getDataMessage($message_id)
    {
        $m_message = $this->getServiceMessageUser()
            ->getMessage($message_id)
            ->getMessage();
        
        return ['id' => $m_message->getId(),'name' => 'message','data' => $m_message];
    }

    
    
    
    
    
    
    /**
     * Get Service Thread Message.
     *
     * @return \Application\Service\ThreadMessage
     */
    private function getServiceThreadMessage()
    {
        return $this->container->get('app_service_thread_message');
    }

    /**
     * Get Service Thread.
     *
     * @return \Application\Service\Thread
     */
    private function getServiceThread()
    {
        return $this->container->get('app_service_thread');
    }

    /**
     * Get Service Event Comment.
     *
     * @return \Application\Service\EventSubscription
     */
    private function getServiceEventSubscription()
    {
        return $this->container->get('app_service_event_subscription');
    }

    /**
     * Get Service User.
     *
     * @return \Application\Service\User
     */
    private function getServiceUser()
    {
        return $this->container->get('app_service_user');
    }

    /**
     * Get Service Post
     *
     * @return \Application\Service\Post
     */
    private function getServicePost()
    {
        return $this->container->get('app_service_post');
    }

    /**
     * Get Service Video Archive.
     *
     * @return \Application\Service\VideoArchive
     */
    private function getServiceVideoArchive()
    {
        return $this->container->get('app_service_video_archive');
    }

    /**
     * Get Service Submission Comments.
     *
     * @return \Application\Service\SubmissionComments
     */
    private function getServiceSubmissionComments()
    {
        return $this->container->get('app_service_submission_comments');
    }

    /**
     * Get Service Submission.
     *
     * @return \Application\Service\Submission
     */
    private function getServiceSubmission()
    {
        return $this->container->get('app_service_submission');
    }

    /**
     * Get Service Subscription
     *
     * @return \Application\Service\Subscription
     */
    private function getServiceSubscription()
    {
        return $this->container->get('app_service_subscription');
    }
    
    
    /**
     * Get Service Course.
     *
     * @return \Application\Service\Course
     */
    private function getServiceCourse()
    {
        return $this->container->get('app_service_course');
    }

    /**
     * Get Service Resume.
     *
     * @return \Application\Service\Resume
     */
    private function getServiceResume()
    {
        return $this->container->get('app_service_resume');
    }

    /**
     * Get Service School.
     *
     * @return \Application\Service\School
     */
    private function getServiceSchool()
    {
        return $this->container->get('app_service_school');
    }

    /**
     * Get Service Contact.
     *
     * @return \Application\Service\Contact
     */
    private function getServiceContact()
    {
        return $this->container->get('app_service_contact');
    }

    /**
     * Get Service Mail.
     *
     * @return \Mail\Service\Mail
     */
    private function getServiceMail()
    {
        return $this->container->get('mail.service');
    }

    /**
     * Get Service Message User.
     *
     * @return \Application\Service\MessageUser
     */
    private function getServiceMessageUser()
    {
        return $this->container->get('app_service_message_user');
    }

    /**
     * Get Service Submission Pg.
     *
     * @return \Application\Service\SubmissionPg
     */
    private function getServiceSubmissionPg()
    {
        return $this->container->get('app_service_submission_pg');
    }

    /**
     * Get Service Message.
     *
     * @return \Application\Service\Page
     */
    private function getServicePage()
    {
        return $this->container->get('app_service_page');
    }
    
    /**
     * Get Service Message.
     *
     * @return \Application\Service\Message
     */
    private function getServiceMessage()
    {
        return $this->container->get('app_service_message');
    }

    /**
     * Get Service Task.
     *
     * @return \Application\Service\Task
     */
    private function getServiceTask()
    {
        return $this->container->get('app_service_task');
    }

    /**
     * Get Service Item
     *
     * @return \Application\Service\Item
     */
    private function getServiceItem()
    {
        return $this->container->get('app_service_item');
    }
}
