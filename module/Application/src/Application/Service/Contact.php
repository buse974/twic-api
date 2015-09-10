<?php
namespace Application\Service;

use Dal\Service\AbstractService;
use Zend\Db\Sql\Predicate\IsNotNull;

class Contact extends AbstractService
{

    /**
     * @invokable
     *
     * @param int $user            
     */
    public function add($user)
    {
        $identity = $this->getServiceUser()->getIdentity();
        
        if ($user == $identity['id']) {
            throw new \Exception('error user equal myself');
        }
        
        $m_contact = $this->getModel()
            ->setUserId($identity['id'])
            ->setContactId($user)
            ->setRequestDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
        $this->getMapper()->insert($m_contact);
        $m_contact = $this->getModel()
            ->setUserId($user)
            ->setContactId($identity['id']);
        
        $ret = $this->getMapper()->insert($m_contact);
        
        if($ret > 1) {
            $this->getServiceEvent()->userRequestconnection($user);
        }
        
        return $ret;
    }

    /**
     * @invokable
     *
     * @param int $user            
     */
    public function accept($user)
    {
        $identity = $this->getServiceUser()->getIdentity();
        $m_contact = $this->getModel()->setAcceptedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
        
        if ($this->getMapper()->update($m_contact, array('user_id' => $user,'contact_id' => $identity['id'],new IsNotNull('request_date'))) <= 0) {
            throw new \Exception('Error accept user');
        }
        
        return $this->getMapper()->update($m_contact, array('user_id' => $identity['id'],'contact_id' => $user));
    }

    /**
     * @invokable
     *
     * @param int $user
     */
    public function remove($user)
    {
        $identity = $this->getServiceUser()->getIdentity();
        
        // $m_contact = $this->getModel()->setDeletedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
        
        $m_contact = $this->getModel()
            ->setUserId($identity['id'])
            ->setContactId($user);
        $this->getMapper()->delete($m_contact);
        
        $m_contact = $this->getModel()
            ->setUserId($user)
            ->setContactId($identity['id']);
        
        return $this->getMapper()->delete($m_contact);
    }

    /**
     * @invokable
     *
     * @param int $school            
     */
    public function addBySchool($school)
    {
        return $this->getMapper()->addBySchool($school) / 2;
    }

    /**
     * @invokable
     *
     * @param string $all            
     */
    public function getListRequest($all = false)
    {
        $identity = $this->getServiceUser()->getIdentity();

        $listRequest = $this->getMapper()->getListRequest($identity['id']);

        foreach ($listRequest as $request) {
            $request->setContact($this->getServiceUser()
                ->get($request->getUserId()));
        }

        return $listRequest;
    }

    /**
     * @invokable
     *
     * @param integer $user
     */
    public function getList($user = null)
    {
        if (null === $user) {
            $user = $this->getServiceUser()->getIdentity();
        }

        if(!$user['id']) {
            throw new \Exception('user parameter without id');
        }

        $listRequest = $this->getMapper()->getList($user['id']);

        foreach ($listRequest as $request) {
            $request->setContact($this->getServiceUser()->get($request->getContactId()));
        }

        return $listRequest;
    }

    public function getListId()
    {
        $user = $this->getServiceUser()->getIdentity()['id'];

        $listRequest = $this->getMapper()->getList($user);
    
        $ret = [];
        
        foreach ($listRequest as $request) {
            $ret[] = $request->getContactId();
        }
    
        return $ret;
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
     * @return \Application\Service\User
     */
    public function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }
}
