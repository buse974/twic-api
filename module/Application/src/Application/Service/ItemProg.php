<?php
namespace Application\Service;

use Dal\Service\AbstractService;
use Application\Model\Item as ModelItem;
use JRpc\Json\Server\Exception\JrpcException;
use Application\Model\Role as ModelRole;

class ItemProg extends AbstractService
{

    /**
     * @invokable
     *
     * @param int $id            
     *
     * @throws \Exception
     *
     * @return array
     */
    public function getSubmission($user, $id)
    {
        $res_item_prog = $this->getMapper()->getSubmission($user, $id);
        if ($res_item_prog->count() > 0) {
            $m_item_prog = $res_item_prog->current();
            $m_item = $m_item_prog->getItem();
            $m_item->setMaterials($this->getServiceMaterialDocument()
                ->getListByItem($m_item->getId()));
            $m_course = $m_item->getCourse();
            $m_item_prog->setUsers(array($this->getServiceUser()
                ->get($user)));
            $m_course->setInstructor($this->getServiceUser()
                ->getListOnly(\Application\Model\Role::ROLE_INSTRUCTOR_STR, $m_course->getId()));
            
            return array('item_prog' => $m_item_prog,'students' => $m_item_prog->getUsers());
        }
        throw new JrpcException('No authorization', - 32029);
    }

    /**
     *
     * @param int $item_assignment            
     *
     * @throws \Exception
     *
     * @return \Application\Model\ItemProg
     */
    public function getByItemAssignment($item_assignment)
    {
        $res_item_prog = $this->getMapper()->getByItemAssignment($item_assignment);
        
        if ($res_item_prog->count() <= 0) {
            throw new \Exception('error select item by itemassignement');
        }
        
        return $res_item_prog->current();
    }

    /**
     * Create Session Programmation.
     *
     * @invokable
     *
     * @param int $item            
     * @param string $start_date            
     * @param string $due_date            
     * @param int|array $users            
     *
     * @throws \Exception
     *
     * @return int
     */
    public function add($item, $start_date, $due_date = null, $users = null)
    {
        $m_item_prog = $this->getModel()
            ->setItemId($item)
            ->setDueDate($due_date)
            ->setStartDate($start_date);
        if ($this->getMapper()->insert($m_item_prog) <= 0) {
            throw new \Exception('error insert item prog');
        }
        $id = $this->getMapper()->getLastInsertValue();
        $m_item = $this->getServiceItem()->get($item);
        
        if (null === $users) {
            $users = [];
        }
        
        if (null !== $users) {
            $ip = $this->getServiceItemProgUser()->add($users, [$id]);
            if (isset($ip[$id])) {
                $ip = $ip[$id];
            }
            $users = array_keys($ip, 1, true);
        }
        
        switch ($m_item->getType()) {
            case ModelItem::TYPE_LIVE_CLASS:
                $instructors = $this->getServiceUser()->getList(null, ModelRole::ROLE_INSTRUCTOR_STR, null, $m_item->getCourseId());
                foreach ($instructors['list'] as $instructor) {
                    $users[] = $instructor['id'];
                }
                $conversation = $this->getServiceConversationUser()->createConversation($users, null, 3);
                $videoconf = $this->getServiceVideoconf()->add('', '', $start_date, $id, $conversation);
                $this->getServiceVideoconfConversation()->add($conversation, $videoconf);
                break;
            case ModelItem::TYPE_WORKGROUP:
                $conversation = $this->getServiceConversationUser()->createConversation($users, null, 3);
                $videoconf = $this->getServiceVideoconf()->add('', '', $start_date, $id, $conversation);
                $this->getServiceVideoconfConversation()->add($conversation, $videoconf);
                $this->getServiceItemAssignment()->add($id);
                break;
            default:
                break;
        }
        
        $this->getServiceEvent()->programmationNew($id);

        return $id;
    }

    public function getListRecord($item, $user, $is_student)
    {
        $res_item_prog = $this->getMapper()->getListRecord($item, $user, $is_student);
        foreach ($res_item_prog as $m_item_prog) {
            $m_item_prog->setVideoconfArchives($this->getServiceVideoconfArchive()
                ->getListRecordByItemProg($m_item_prog->getId()));
            $m_item_prog->setUsers($this->getServiceUser()
                ->getListByItemProg($m_item_prog->getId()));
        }
        
        return $res_item_prog;
    }

    /**
     * Update User.
     *
     * @invokable
     *
     * @param int $id            
     * @param string $start_date            
     * @param string $due_date            
     * @param array $users            
     *
     * @return int
     */
    public function update($id, $start_date = null, $due_date = null, $users = null)
    {
        $m_item_prog = $this->getModel();
        
        $m_item_prog->setId($id)
            ->setStartDate($start_date)
            ->setDueDate($due_date);
        
        if ($users !== null) {
            $this->getServiceItemProgUser()->deleteByItemProg($id);
            $this->getServiceItemProgUser()->add($users, array($id));
            
            $m_item = $this->getServiceItem()->getByItemProg($id);
            
            switch ($m_item->getType()) {
                case ModelItem::TYPE_LIVE_CLASS:
                    $instructors = $this->getServiceUser()->getList(null, ModelRole::ROLE_INSTRUCTOR_STR, null, $m_item->getCourseId());
                    foreach ($instructors['list'] as $instructor) {
                        $users[] = $instructor['id'];
                    }
                    $m_videoconf = $this->getServiceVideoconf()->getByItemProg($id);
                    $this->getServiceConversationUser()->replace($m_videoconf->getConversationId(), $users);
                    break;
                case ModelItem::TYPE_WORKGROUP:
                    $m_videoconf = $this->getServiceVideoconf()->getByItemProg($id);
                    $this->getServiceConversationUser()->replace($m_videoconf->getConversationId(), $users);
                    break;
                default:
                    break;
            }
        }
        
        if (null !== $start_date) {
            $this->getServiceVideoconf()->updateByItemProg($id, $start_date);
        }
        
        $ret = $this->getMapper()->update($m_item_prog);
        $this->getServiceEvent()->programmationUpdated($id);
        
        return $ret;
    }

    /**
     * @invokable
     *
     * @param int $id            
     *
     * @return int
     */
    public function delete($id)
    {
        $this->getServiceItemProgUser()->deleteByItemProg($id);
        $this->getServiceItemAssignment()->deleteByItemProg($id);
        
        return $this->getMapper()->delete($this->getModel()
            ->setId($id));
    }

    /**
     * add User in a programmation.
     *
     * @invokable
     *
     * @param int|array $item_prog            
     * @param int|array $user            
     */
    public function addUser($item_prog, $user)
    {
        if (! is_array($user)) {
            $user = array($user);
        }
        if (! is_array($item_prog)) {
            $item_prog = array($item_prog);
        }
        
        $this->getServiceItemProgUser()->add($user, $item_prog);
        
        $users = [];
        foreach ($this->getServiceUser()->getListByItemProg($item_prog) as $u) {
            $users[] = $u->getId();
        }
        
        foreach ($item_prog as $ip) {
            $m_item = $this->getServiceItem()->getByItemProg($ip);
            switch ($m_item->getType()) {
                case ModelItem::TYPE_LIVE_CLASS:
                    $m_videoconf = $this->getServiceVideoconf()->getByItemProg($ip);
                    $conversation = $this->getServiceConversationUser()->add($m_videoconf->getConversationId(), $users);
                    break;
                case ModelItem::TYPE_WORKGROUP:
                    $m_videoconf = $this->getServiceVideoconf()->getByItemProg($ip);
                    $conversation = $this->getServiceConversationUser()->add($m_videoconf->getConversationId(), $users);
                    break;
                default:
                    break;
            }
        }
        
        return $users;
    }

    /**
     * @invokable
     * 
     * @param integer $item
     * @param string $start
     * @param string $end
     * @param integer $course
     * @param integer $id
     */
    public function getList($item = null, $start = null, $end = null, $course = null, $id = null)
    {
        $me = $this->getServiceUser()->getIdentity();
        $res_item_progs = $this->getMapper()->getList($me, $item, $start, $end, $course, $id);
        foreach ($res_item_progs as $m_item_prog) {
            $m_item_prog->setUsers($this->getServiceUser()->getListByItemProg($m_item_prog->getId()));
            if (in_array(ModelRole::ROLE_STUDENT_STR, $me['roles'])) {
                $m_item_prog->setItemProgUser($this->getServiceItemProgUser()->getListByItemProg($m_item_prog->getId(), $me['id'])->current());
            } else {
                $m_item_prog->setItemProgUser($this->getServiceItemProgUser()->getListByItemProg($m_item_prog->getId()));
            }
        }
        
        return $res_item_progs;
    }

    /**
     * @param integer $id
     * 
     * @return \Application\Model\ItemProg
     */
    public function get($id)
    {
        return $this->getMapper()->get($id)->current();
    }
    
    public function deleteByItem($item)
    {
        $res_item_prog = $this->getMapper()->select($this->getModel()
            ->setItemId($item));
        
        foreach ($res_item_prog as $m_item_prog) {
            $this->getServiceItemProgUser()->deleteByItemProg($m_item_prog->getId());
            $this->getServiceItemAssignment()->deleteByItemProg($m_item_prog->getId());
        }
        
        $this->getMapper()->delete($this->getModel()
            ->setItemId($item));
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
     * @return \Application\Service\Videoconf
     */
    public function getServiceVideoconf()
    {
        return $this->getServiceLocator()->get('app_service_videoconf');
    }

    /**
     *
     * @return \Application\Service\Item
     */
    public function getServiceItem()
    {
        return $this->getServiceLocator()->get('app_service_item');
    }

    /**
     *
     * @return \Application\Service\ItemProgUser
     */
    public function getServiceItemProgUser()
    {
        return $this->getServiceLocator()->get('app_service_item_prog_user');
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
     * @return \Application\Service\User
     */
    public function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }
    
    /**
     *
     * @return \Application\Service\Event
     */
    public function getServiceEvent()
    {
        return $this->getServiceLocator()->get('app_service_event');
    }

    /**
     *
     * @return \Application\Service\ConversationUser
     */
    public function getServiceConversationUser()
    {
        return $this->getServiceLocator()->get('app_service_conversation_user');
    }

    /**
     *
     * @return \Application\Service\VideoconfConversation
     */
    public function getServiceVideoconfConversation()
    {
        return $this->getServiceLocator()->get('app_service_videoconf_conversation');
    }

    /**
     *
     * @return \Zend\Authentication\AuthenticationService
     */
    public function getServiceAuth()
    {
        return $this->getServiceLocator()->get('auth.service');
    }

    /**
     *
     * @return \Application\Service\MaterialDocument
     */
    public function getServiceMaterialDocument()
    {
        return $this->getServiceLocator()->get('app_service_material_document');
    }
}
