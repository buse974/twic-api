<?php
namespace Application\Service;

use Dal\Service\AbstractService;
use Dal\Db\ResultSet\ResultSet;

class Thread extends AbstractService
{

    /**
     * Add thread.
     *
     * @invokable
     *
     * @param string $title            
     * @param integer $course            
     * @param string $message            
     * @throws \Exception
     *
     * @return integer
     */
    public function add($title, $course, $message = null)
    {
        $m_thread = $this->getModel()
            ->setCourseId($course)
            ->setTitle($title)
            ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'))
            ->setUserId($this->getServiceAuth()
            ->getIdentity()
            ->getId());
        
        if ($this->getMapper()->insert($m_thread) <= 0) {
            throw new \Exception('error insert thread');
        }
        
        $id = $this->getMapper()->getLastInsertValue();
        if (null !== $message) {
            $id = $this->getServiceThreadMessage()->add($message, $id);
        }
        
        $this->getServiceEvent()->threadNew($id);
        
        return $id;
    }

    /**
     * update thread.
     *
     * @invokable
     *
     * @TODO Add updated date
     *
     * @param int $id            
     * @param string $title            
     *
     * @return int
     */
    public function update($id, $title)
    {
        $m_thread = $this->getModel()->setTitle($title);
        // ->setUpdatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
        
        return $this->getMapper()->update($m_thread, array('id' => $id,'user_id' => $this->getServiceAuth()
            ->getIdentity()
            ->getId()));
    }

    /**
     * GetList Thread by course.
     *
     * @invokable
     *
     * @param int $course            
     *
     * @throws \Exception
     *
     * @return ResultSet
     */
    public function getList($course, $filter = null)
    {
        $mapper = $this->getMapper();
        
        $res_thread = $mapper->usePaginator($filter)->getList($course);
        
        if ($res_thread->count() <= 0) {
            throw new \Exception('not thread with course id: ' . $course);
        }
        
        foreach ($res_thread as $m_thread) {
           $m_thread->setMessage($this->getServiceThreadMessage()->getLast($m_thread->getId()));
           
           $roles = [];
           foreach ($this->getServiceRole()->getRoleByUser($m_thread->getUser()->getId()) as $role) {
               $roles[] = $role->getName();
           }
           $m_thread->getUser()->setRoles($roles);
        }
        
        return array('count' => $mapper->count(), 'list' => $res_thread);
    }

    /**
     * 
     * @param integer $thread
     * 
     * @return \Application\Model\Thread
     */
    public function get($thread) 
    {
        return $this->getMapper()->getList(null, $thread)->current();
    }
    
    /**
     * delete thread.
     *
     * @invokable
     *
     * @param int $id            
     */
    public function delete($id)
    {
        return $this->getMapper()->update($this->getModel()
            ->setDeletedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s')), array('user_id' => $this->getServiceAuth()
            ->getIdentity()
            ->getId(),'id' => $id));
    }

    /**
     *
     * @return \Auth\Service\AuthService
     */
    public function getServiceAuth()
    {
        return $this->getServiceLocator()->get('auth.service');
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
     * @return \Application\Service\Role
     */
    public function getServiceRole()
    {
        return $this->getServiceLocator()->get('app_service_role');
    }
    
    /**
     *
     * @return \Application\Service\ThreadMessage
     */
    public function getServiceThreadMessage()
    {
        return $this->getServiceLocator()->get('app_service_thread_message');
    }
}
