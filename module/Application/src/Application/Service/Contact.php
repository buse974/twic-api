<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Contact
 */
namespace Application\Service;

use Dal\Service\AbstractService;
use Zend\Db\Sql\Predicate\IsNull;
use Zend\Db\Sql\Predicate\IsNotNull;

/**
 * Class Contact.
 */
class Contact extends AbstractService
{
    /**
     * Request Contact.
     * 
     * @invokable
     *
     * @param int $user
     *
     * @return int
     */
    public function add($user)
    {
        $identity = $this->getServiceUser()->getIdentity();

        if ($user == $identity['id']) {
            throw new \Exception('error user equal myself');
        }

        $date = (new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s');

        $m_contact = $this->getModel()
            ->setUserId($identity['id'])
            ->setContactId($user);

        $m_contact_me = $this->getModel()
            ->setRequestDate($date)
            ->setAcceptedDate(new IsNull())
            ->setDeletedDate(new IsNull())
            ->setRequested(true)
            ->setAccepted(false)
            ->setDeleted(false);

        $m_contact_you = $this->getModel()
            ->setRequestDate($date)
            ->setAcceptedDate(new IsNull())
            ->setDeletedDate(new IsNull())
            ->setRequested(false)
            ->setAccepted(false)
            ->setDeleted(false);

        if ($this->getMapper()
            ->select($m_contact)
            ->count() === 0) {
            $m_contact_me->setUserId($identity['id'])->setContactId($user);
            $m_contact_you->setUserId($user)->setContactId($identity['id']);
            $this->getMapper()->insert($m_contact_me);
            $ret = $this->getMapper()->insert($m_contact_you);
        } else {
            $this->getMapper()->update($m_contact_me, array(
                'user_id' => $identity['id'],
                'contact_id' => $user,
            ));
            $ret = $this->getMapper()->update($m_contact_you, array(
                'user_id' => $user,
                'contact_id' => $identity['id'],
            ));
        }

        $this->getServiceEvent()->userRequestconnection($user);

        return $ret;
    }

    /**
     * Accept Contact.
     * 
     * @invokable
     *
     * @param int $user
     *
     * @return bool
     */
    public function accept($user)
    {
        $identity = $this->getServiceUser()->getIdentity();
        $date = (new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s');

        $m_contact = $this->getModel()
            ->setAcceptedDate($date)
            ->setAccepted(false);
        $this->getMapper()->update($m_contact, array(
            'user_id' => $user,
            'contact_id' => $identity['id'],
        ));

        $m_contact = $this->getModel()
            ->setAcceptedDate($date)
            ->setAccepted(true);
        $this->getMapper()->update($m_contact, array(
            'user_id' => $identity['id'],
            'contact_id' => $user,
        ));

        $this->getServiceEvent()->userAddConnection($identity['id'], $user);

        return true;
    }

    /**
     * Remove Contact.
     * 
     * @invokable
     *
     * @param int $user
     *
     * @return bool
     */
    public function remove($user)
    {
        $identity = $this->getServiceUser()->getIdentity();
        $date = (new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s');

        $m_contact = $this->getModel()
            ->setDeletedDate($date)
            ->setDeleted(false);
        $this->getMapper()->update($m_contact, array(
            'user_id' => $user,
            'contact_id' => $identity['id'],
        ));

        $m_contact = $this->getModel()
            ->setDeletedDate($date)
            ->setDeleted(true);
        $this->getMapper()->update($m_contact, array(
            'user_id' => $identity['id'],
            'contact_id' => $user,
        ));

        $this->getServiceEvent()->userDeleteConnection($identity['id'], $user);

        return true;
    }

    /**
     * Add Contact all school.
     * 
     * @invokable
     *
     * @param int $school
     *
     * @return int
     */
    public function addBySchool($school)
    {
        return $this->getMapper()->addBySchool($school) / 2;
    }

    /**
     * Get List Request Contact.
     * 
     * @invokable
     *
     * @param bool $all
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListRequest($all = false)
    {
        $me = $this->getServiceUser()->getIdentity()['id'];
        $listRequest = $this->getMapper()->getListRequest($me);
        foreach ($listRequest as $request) {
            $request->setContact($this->getServiceUser()
                ->get($request->getContactId()));
        }

        return $listRequest;
    }

    /**
     * Get List Contact.
     * 
     * @invokable
     *
     * @param int   $user
     * @param array $exclude
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getList($user = null, $exclude = null)
    {
        if (null === $user) {
            $user = $this->getServiceUser()->getIdentity();
        }

        if (!$user['id']) {
            throw new \Exception('user parameter without id');
        }

        $listRequest = $this->getMapper()->getList($user['id'], $exclude);
        foreach ($listRequest as $request) {
            $request->setContact($this->getServiceUser()
                ->get($request->getContactId()));
        }

        return $listRequest;
    }

    /**
     * Get List Id of contact.
     * 
     * @param int $user
     *
     * @return array
     */
    public function getListId($user = null)
    {
        if (null === $user) {
            $user = $this->getServiceUser()->getIdentity()['id'];
        }

        $listRequest = $this->getMapper()->getList($user);

        $ret = [];

        foreach ($listRequest as $request) {
            $ret[] = $request->getContactId();
        }

        return $ret;
    }
    
      /**
     * Get list contact id by users.
     *
     * @invokable
     * 
     * @param int|array $id
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function m_getListIdByUser($id)
    {
        if(!is_array($id)){
            $users = [$id];
        }
        else{
            $users = $id;
        }
        $contacts = [];
        foreach($users as &$user){
            $contacts[$user] = [];
        }
        $res_contact = $this->getMapper()->select($this->getModel()->setUserId($users)->setAcceptedDate(new IsNotNull())->setDeletedDate(new IsNull()));
        foreach($res_contact->toArray() as &$contact){
            $contacts[$contact['user_id']][] = $contact['contact_id'];
        }

        return $contacts;
    }
    
      /**
     * Get list contact id by users.
     *
     * @invokable
     * 
     * @param int|array $id
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function m_getListRequestByUser($id)
    {
        if(!is_array($id)){
            $users = [$id];
        }
        else{
            $users = $id;
        }
        $contacts = [];
        foreach($users as &$user){
            $contacts[$user] = [];
        }
        $res_contact = $this->getMapper()->select($this->getModel()->setUserId($users)->setAcceptedDate(new IsNull())->setDeletedDate(new IsNull()));
        foreach($res_contact->toArray() as &$contact){
            $contacts[$contact['user_id']][] = $contact['contact_id'];
        }

        return $contacts;
    }
    
      /**
     * Get list contact id by users.
     *
     * @invokable
     * 
     * @param int|array $id
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function m_getListRequestByContact($id)
    {
        if(!is_array($id)){
            $users = [$id];
        }
        else{
            $users = $id;
        }
        $contacts = [];
        foreach($users as &$user){
            $contacts[$user] = [];
        }
        $res_contact = $this->getMapper()->select($this->getModel()->setContactId($users)->setAcceptedDate(new IsNull())->setDeletedDate(new IsNull()));
        foreach($res_contact->toArray() as &$contact){
            $contacts[$contact['contact_id']][] = $contact['user_id'];
        }

        return $contacts;
    }
    

    /**
     * Get Service Event.
     * 
     * @return \Application\Service\Event
     */
    private function getServiceEvent()
    {
        return $this->container->get('app_service_event');
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
}
