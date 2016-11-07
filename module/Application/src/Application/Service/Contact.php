<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Contact
 */
namespace Application\Service;

use Dal\Service\AbstractService;
use Zend\Db\Sql\Predicate\IsNull;
use Application\Model\Role as ModelRole;
use ZendService\Google\Gcm\Notification as GcmNotification;

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
        $user_id = $identity['id'];
        if ($user == $user_id) {
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

        
        $m_user = $this->getServiceUser()->getLite($user_id);
        $name = "";
        if(!is_object($m_user->getNickname()) &&  null !== $m_user->getNickname()) {
            $name = $m_user->getNickname();
        } else {
            if(!is_object($m_user->getFirstname()) &&  null !== $m_user->getFirstname()) {
                $name = $m_user->getFirstname();
            }
            if(!is_object($m_user->getLastname()) &&  null !== $m_user->getLastname()) {
                $name .= ' '.$m_user->getLastname();
            }
        }
        
        $gcm_notification->setTitle($name)
            ->setSound("default")
            ->setColor("#00A38B")
            ->setBody('Sent you a connection request');
        
        $this->getServiceFcm()->send($user, ['data' => [
            'state' => 'request',
            'user' => $user_id,
            ]
        ], $gcm_notification);
        
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
        $user_id = $identity['id'];
        $date = (new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s');

        $m_contact = $this->getModel()
            ->setAcceptedDate($date)
            ->setAccepted(false);
        $this->getMapper()->update($m_contact, array(
            'user_id' => $user,
            'contact_id' => $user_id,
        ));

        $m_contact = $this->getModel()
            ->setAcceptedDate($date)
            ->setAccepted(true);
        $this->getMapper()->update($m_contact, array(
            'user_id' => $user_id,
            'contact_id' => $user,
        ));

        $this->getServiceSubscription()->add('PU'.$user, $user_id);
        $this->getServiceSubscription()->add('EU'.$user, $user_id);
        $this->getServiceSubscription()->add('PU'.$user_id, $user);
        $this->getServiceSubscription()->add('EU'.$user_id, $user);
        $this->getServiceEvent()->userAddConnection($user_id, $user);

        $m_user = $this->getServiceUser()->getLite($user_id);
        $name = "";
        if(!is_object($m_user->getNickname()) &&  null !== $m_user->getNickname()) {
            $name = $m_user->getNickname();
        } else {
            if(!is_object($m_user->getFirstname()) &&  null !== $m_user->getFirstname()) {
                $name = $m_user->getFirstname();
            }
            if(!is_object($m_user->getLastname()) &&  null !== $m_user->getLastname()) {
                $name .= ' '.$m_user->getLastname();
            }
        }
        
        $gcm_notification = new GcmNotification();
        $gcm_notification->setTitle($name)
            ->setSound("default")
            ->setColor("#00A38B")
            ->setBody('Accepted your request');
        
        $this->getServiceFcm()->send($user, [
            'data' => [
                'state' => 'accept',
                'user' => $user_id,
            ]
        ], $gcm_notification);
        
        return true;
    }

    /**
     * Remove Contact
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
        $user_id = $identity['id'];
        $date = (new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s');

        $m_contact = $this->getModel()
            ->setDeletedDate($date)
            ->setDeleted(false);
        $this->getMapper()->update($m_contact, array(
            'user_id' => $user,
            'contact_id' => $user_id,
        ));

        $m_contact = $this->getModel()
            ->setDeletedDate($date)
            ->setDeleted(true);
        $this->getMapper()->update($m_contact, array(
            'user_id' => $user_id,
            'contact_id' => $user,
        ));

        $this->getServiceSubscription()->delete('PU'.$user, $user_id);
        $this->getServiceSubscription()->delete('EU'.$user, $user_id);
        
        $this->getServiceSubscription()->delete('PU'.$user_id, $user);
        $this->getServiceSubscription()->delete('EU'.$user_id, $user);
        
        $this->getServiceEvent()->userDeleteConnection($user_id, $user);

        $this->getServiceFcm()->send($user, [
            'data' => [
                'state' => 'remove',
                'user' => $user_id,
            ]
        ]);
        
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
     * Get User for mobile
     *
     * @invokable
     *
     * @param int|array $id            
     * @return array
     */
    public function m_getList($search = null, $exclude = null, $filter = null)
    {
        $identity = $this->getServiceUser()->getIdentity();
        if(null !== $exclude && !is_array($exclude)){
            $exclude = [$exclude];
        }
        
        $is_sadmin_admin = (in_array(ModelRole::ROLE_SADMIN_STR, $identity['roles']) || in_array(ModelRole::ROLE_ADMIN_STR, $identity['roles']));
          
        $mapper = $this->getServiceUser()->getMapper();
        $res = $mapper->usePaginator($filter)->getList($identity['id'], $is_sadmin_admin, $filter, null, null, null, null, null, $search, null, null, false, null, $exclude, null, 3);
        
        $res = $res->toArray();
        $users = [];
        foreach ($res as &$user) {
            $users[] = $user['id'];
        }
        
        return ['list' => $users,'count' => $mapper->count()];
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
        $res_contact = $this->getMapper()->getList($id);
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
        $res_contact = $this->getMapper()->getListRequest(null, $id);
        foreach($res_contact->toArray() as &$contact){
            $contacts[$contact['contact_id']][] = $contact['user_id'];
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
        $res_contact = $this->getMapper()->getListRequest($id);
        foreach($res_contact->toArray() as &$contact){
            $contacts[$contact['user_id']][] = $contact['contact_id'];
        }

        return $contacts;
    }
    

    /**
     * Get Service Event
     * 
     * @return \Application\Service\Event
     */
    private function getServiceEvent()
    {
        return $this->container->get('app_service_event');
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
     * Get Service User.
     *
     * @return \Application\Service\User
     */
    private function getServiceUser()
    {
        return $this->container->get('app_service_user');
    }
    
    /**
     * Get Service Service Conversation User
     *
     * @return \Application\Service\Fcm
     */
    private function getServiceFcm()
    {
        return $this->container->get('fcm');
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
    
    
}
