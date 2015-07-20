<?php

namespace Application\Service;

use Dal\Service\AbstractService;

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
        
        if($user == $identity['id']) {
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

        return $this->getMapper()->insert($m_contact);
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

        $this->getMapper()->update($m_contact, array('user_id' => $user, 'contact_id' => $identity['id']));

        return $this->getMapper()->update($m_contact, array('user_id' => $identity['id'], 'contact_id' => $user));
    }

    /**
     * @invokable
     *
     * @param int $user
     */
    public function remove($user)
    {
        $identity = $this->getServiceUser()->getIdentity();
        $m_contact = $this->getModel()->setDeletedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));

        $this->getMapper()->update($m_contact, array('user_id' => $user, 'contact_id' => $identity['id']));

        return $this->getMapper()->update($m_contact, array('user_id' => $identity['id'], 'contact_id' => $user));
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

        return $this->getMapper()->getListRequest($identity['id']);
    }

    /**
     * @invokable
     */
    public function getList()
    {
        $identity = $this->getServiceUser()->getIdentity();

        return $this->getMapper()->getList($identity['id']);
    }

    /**
     * @return \Application\Service\User
     */
    public function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }
}
