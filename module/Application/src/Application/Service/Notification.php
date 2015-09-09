<?php
namespace Application\Service;

use Dal\Service\AbstractService;

class Notification extends AbstractService
{

    /**
     * create notification
     *
     * @param string $event            
     * @param string $source            
     * @param string $object            
     * @param array $user            
     * @throws \Exception
     * @return integer
     */
    public function create($event, $source, $object, $user)
    {
        $m_notification = $this->getModel()
            ->setEvent($event)
            ->setSource(json_encode($source))
            ->setObject(json_encode($object))
            ->setDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
        
        if ($this->getMapper()->insert($m_notification) <= 0) {
            throw new \Exception('error insert notification');
        }
        
        $notification_id = $this->getMapper()->getLastInsertValue();
        
        $this->getServiceNotificationUser()->add($user, $notification_id);
        
        return $notification_id;
    }

    /**
     * @invokable
     */
    public function getList($filter = null)
    {
        $mapper = $this->getMapper();
        $me = $this->getServiceUser()->getIdentity()['id'];
        $res_notification = $mapper->usePaginator($filter)->getList($me);
        
        return ['list' => $res_notification,'count' => $mapper->count()];
    }

    
    
    
    
    
    
    
    
    
    
    
    
    // notification 
    public function userPublication($feed, $users)
    {
        return $this->create('user.publication', $this->getDataUser(), $this->getDataFeed($feed), $users);
    }
    
    public function userLike($feed, $users)
    {
        return $this->create('user.like', $this->getDataUser(), $this->getDataFeed($feed), $users);
    }

    public function userAddConnection($user, $users)
    {
        return $this->create('user.addconnection', $this->getDataUser(), $this->getDataUser($user), $users);
    }
    
    public function studentSubmitAssignment($item_assignment)
    {
        $m_item_assignment = $this->getServiceItemAssignment()->get($item_assignment);
        
        $res_user = $m_item_assignment->getItemProg()->getItem()->getCourse()->getInstructor();
        
        $users = [];
        foreach ($res_user as $m_user) {
            $users = $m_user->getId();
        }
        
        return $this->create('student.submit.assignment', 
            $this->getDataUser(), 
            $this->getDataAssignment($m_item_assignment), $users);
    }

    public function assignmentGraded($item_assignment)
    {
        $m_item_assignment = $this->getServiceItemAssignment()->get($item_assignment);
        
        $res_user = $m_item_assignment->getStudents();
        $users = [];
        foreach ($res_user as $m_user) {
            $users = $m_user->getId();
        }
        
        return $this->create(
            'assignment.graded', 
            $this->getDataUser(), 
            $this->getDataAssignmentGrade($m_item_assignment), 
            $users);
    }
    
    public function assignmentCommented($item_assignment, $item_assignment_comment)
    {
        $m_item_assignment = $this->getServiceItemAssignment()->get($item_assignment);
        $m_assignment_comment = $this->getServiceItemAssignmentComment()->get($item_assignment_comment);
        
        $res_user = $m_item_assignment->getStudents();
        $users = [];
        foreach ($res_user as $m_user) {
            $users = $m_user->getId();
        }
    
        return $this->create(
            'assignment.commented',
            $this->getDataUser(),
            $this->getDataAssignmentComment($m_item_assignment, $m_assignment_comment),
            $users);
    }
    
    public function threadNew($thread)
    {
        $m_thread = $this->getServiceThread()->get($thread);
        
        $users = $this->getDataUserByCourse($m_thread->getCourse()->getId());
        
        
        return $this->create(
            'thread.new',
            $this->getDataUser(),
            $this->getDataThread($m_thread),
            $users);
    }
    
    public function threadMessage($thread_message)
    {
        $m_thread_message = $this->getServiceThreadMessage()->get($thread_message);
    
        $users = $this->getDataUserByCourse($m_thread_message->getThread()->getCourseId());
    
        return $this->create(
            'thread.message',
            $this->getDataUser(),
            $this->getDataThreadMessage($m_thread_message),
            $users);
    }

    public function recordAvailable($item_prog, $videoconf_archive)
    {
        $m_item_prog = $this->getServiceItemProg()->get($item_prog);
        $m_videoconf_archive = $this->getServiceVideoconfArchive()->get($videoconf_archive);
        
        return $this->create(
            'record.available',
            $this->getDataItemProg($m_item_prog),
            $this->getDataVideoArchive($m_videoconf_archive),
            $this->getDataUserByItemProg($m_item_prog->getId()));
    }
    
    public function eqcqAvailable($item_prog, $videoconf_archive)
    {
        $m_item_prog = $this->getServiceItemProg()->get($item_prog);
        $m_videoconf_archive = $this->getServiceVideoconfArchive()->get($videoconf_archive);
    
        return $this->create(
            'eqcq.available',
            $this->getDataItemProgWihtUser($m_item_prog),
            [],
            $this->getDataUserByItemProg($m_item_prog->getId()));
    }
    
    
    
    
    
    
    

    // ------------- DATA OBJECT -------------------
    public function getDataVideoArchive(\Application\Model\VideoconfArchive $m_videoconf_archive)
    {
        return [
            'id' => $m_videoconf_archive->getId(),
            'name' => 'archive',
            'data' => [
                'archive_link' => $m_videoconf_archive->getArchiveLink(),
            ],
        ];
    }
    
    public function getDataItemProgWihtUser(\Application\Model\ItemProg $m_item_prog)
    {
        $res_user = $this->getServiceUser()->getListByItemProg($m_item_prog->getId());
        
        $users=[];
        foreach ($res_user as $m_user) {
            $users[] =  [
                'firstname' => $m_user->getFirstname(),
                'lastname' => $m_user->getLastname(),
                'avatar' => $m_user->getAvatar(),
            ];
        }
        
        return [
            'id' => $m_item_prog->getId(),
            'name' => 'programming',
            'data' => [
                'start_date' => $m_item_prog->getStartDate(),
                'item' => [
                    'id' => $m_item_prog->getItem()->getId(),
                    'title' => $m_item_prog->getItem()->getTitle(),
                    'type' => $m_item_prog->getItem()->getType(),
                ],
                'users' => $users,
            ],
        ];
    }
    
    public function getDataItemProg(\Application\Model\ItemProg $m_item_prog)
    {
        return [
            'id' => $m_item_prog->getId(),
            'name' => 'thread.message',
            'data' => [
                'start_date' => $m_item_prog->getStartDate(),
                'item' => [
                    'id' => $m_item_prog->getItem()->getId(),
                    'title' => $m_item_prog->getItem()->getTitle(),
                    'type' => $m_item_prog->getItem()->getType(),
                ],
            ],
        ];
    }
    
    public function getDataUserByCourse($course)
    {
        $res_user = $this->getServiceUser()->getListUserBycourse($course);
        
        $users=[];
        foreach ($res_user as $m_user) {
            $users[] = $m_user->getId();
        }
        
        return $users;
    }
    
    public function getDataUserByItemProg($item_prog)
    {
        $res_user = $this->getServiceUser()->getListByItemProg($item_prog);
    
        $users=[];
        foreach ($res_user as $m_user) {
            $users[] = $m_user->getId();
        }
    
        return $users;
    }
    
    public function getDataThreadMessage(\Application\Model\ThreadMessage $m_thread_message)
    {
        $m_thread = $this->getServiceThread()->get($m_thread_message->getThread()->getId());
        
        return [
            'id' => $m_thread_message->getId(),
            'name' => 'thread.message',
            'data' => [
                'message' => $m_thread_message->getMessage(),
                'thread' => $this->getDataThread($m_thread),
            ]
        ];
    }
    
    public function getDataThread(\Application\Model\Thread $m_thread) 
    {
        return [
            'id' => $m_thread->getId(),
            'name' => 'thread',
            'data' => [
                'id' => $m_thread->getId(),
                'title' => $m_thread->getTitle(),
                'course' => [
                    'id' => $m_thread->getCourse()->getId(),
                    'title' => $m_thread->getCourse()->getTitle(),
                ]
            ]
        ];
    }
    
    public function getDataFeed($feed)
    {
        $m_feed = $this->getServiceFeed()->get($feed);
        
        return [
            'id' => $feed,
            'name' => 'feed',
            'data' => [
                'content' => $m_feed->getContent(),
                'picture' => $m_feed->getPicture(),
                'name_picture' => $m_feed->getNamePicture(),
                'document' => $m_feed->getDocument(),
                'name_document' => $m_feed->getNameDocument(),
                'link' => $m_feed->getLink()
            ]
        ];
    }
    
    public function getDataAssignmentComment(\Application\Model\ItemAssignment $m_item_assignment,\Application\Model\ItemAssignmentComment $m_comment)
    {
        return [
            'id' =>  $m_item_assignment->getId(),
            'name' => 'assignment',
            'data' => [
                'item' => [
                    'title' => $m_item_assignment->getItemProg()->getItem()->getTitle(),
                    'type' => $m_item_assignment->getItemProg()->getItem()->getType()
                ],
                'module' => [
                    'title' => $m_item_assignment->getItemProg()->getItem()->getModule()->getTitle(),
                    'course' => [
                        'id' => $m_item_assignment->getItemProg()->getItem()->getCourse()->getId(),
                        'title' => $m_item_assignment->getItemProg()->getItem()->getCourse()->getTitle(),
                    ],
                ],
                'comment' => [
                    'id' =>  $m_comment->getId(),
                    'text' => $m_comment->getText(),
                ],
            ],
        ];
    }
    
    public function getDataAssignmentGrade(\Application\Model\ItemAssignment $m_item_assignment)
    {
        return [
            'id' =>  $m_item_assignment->getId(),
            'name' => 'assignment',
            'data' => [
                'item' => [
                    'title' => $m_item_assignment->getItemProg()->getItem()->getTitle(),
                    'type' => $m_item_assignment->getItemProg()->getItem()->getType()
                ],
                'module' => [
                    'title' => $m_item_assignment->getItemProg()->getItem()->getModule()->getTitle(),
                    'course' => [
                        'id' => $m_item_assignment->getItemProg()->getItem()->getCourse()->getId(),
                        'title' => $m_item_assignment->getItemProg()->getItem()->getCourse()->getTitle(),
                    ],
                ],
            ],
        ];
    }
    
    public function getDataAssignment(\Application\Model\ItemAssignment $m_item_assignment) 
    {
        $users=[];
        foreach ($m_item_assignment->getStudents() as $student) {
            $users[] = [
                    'id' => $student->getId(), 
                    'name' => 'user',
                    'data' => [
                        'firstname' => $student->getFirstname(),
                        'lastname' => $student->getLastname(),
                        'avatar' => $student->getAvatar(),
                        'school' => [
                            'id' => $student->getSchool()->getId(),
                            'short_name' => $student->getSchool()->getShortName(),
                            'logo' => $student->getSchool()->getLogo()
                        ],
                        'user_roles' => $student->getRoles()
                    ]
                ];
        }
        
        return [
            'id' =>  $m_item_assignment->getId(),
            'name' => 'assignment',
            'data' => [
                'users' => $users,
                'item' => [
                    'title' => $m_item_assignment->getItemProg()->getItem()->getTitle(),
                    'type' => $m_item_assignment->getItemProg()->getItem()->getType()
                ],
                'item_prog' => [
                    'id' => $m_item_assignment->getItemProg()->getId()
                ],
                'module' => [
                    'title' => $m_item_assignment->getItemProg()->getItem()->getModule()->getTitle(),
                    'course' => [
                        'id' => $m_item_assignment->getItemProg()->getItem()->getCourse()->getId(),
                        'title' => $m_item_assignment->getItemProg()->getItem()->getCourse()->getTitle(),
                    ],
                ],
            ],
        ];
    }
    
    public function getDataUser($id = null) 
    {
        if(null == $id) {
            $id = $this->getServiceUser()->getIdentity()['id'];
        }
        
        $m_user = $this->getServiceUser()->get($id);
        return  [
            'id' => $id, 
            'name' => 'user', 
            'data' => [
                'firstname' => $m_user['firstname'],
                'lastname' => $m_user['lastname'],
                'avatar' => $m_user['avatar'],
                'school' => [
                    'id' => $m_user['school']['id'],
                    'short_name' => $m_user['school']['short_name'],
                    'logo' => $m_user['school']['logo']
                ],
                'user_roles' => $m_user['roles']
            ]
        ];
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
     * @return \Application\Service\Thread
     */
    public function getServiceThread()
    {
        return $this->getServiceLocator()->get('app_service_thread');
    }
    
    /**
     *
     * @return \Application\Service\NotificationUser
     */
    public function getServiceNotificationUser()
    {
        return $this->getServiceLocator()->get('app_service_notification_user');
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
     * @return \Application\Service\ItemAssignmentComment
     */
    public function getServiceItemAssignmentComment()
    {
        return $this->getServiceLocator()->get('app_service_item_assignment_comment');
    }
}