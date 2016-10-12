<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Message User
 */
namespace Application\Service;

use Dal\Service\AbstractService;
use Zend\Db\Sql\Predicate\IsNull;
use Zend\Db\Sql\Predicate\IsNotNull;
use ZendService\Google\Gcm\Message as GcmMessage;
use ZendService\Google\Gcm\Notification as GcmNotification;

/**
 * Class MessageUser.
 */
class MessageUser extends AbstractService
{
    /**
     * Send message.
     * 
     * @param int   $message_id
     * @param int   $conversation_id
     * @param array $to
     *
     * @throws \Exception
     *
     * @return int
     */
    public function send($message_id, $conversation_id, $to = null)
    {
        $me = $this->getServiceUser()->getIdentity()['id'];

        $for_me = false;
        if (null === $to) {
            $res_conversation_user = $this->getServiceConversationUser()->getUserByConversation($conversation_id);
            foreach ($res_conversation_user as $m_conversation_user) {
                $to[] = $m_conversation_user->getUserId();
            }
        } else {
            $for_me = (in_array($me, $to));
            if (!$for_me) {
                $to[] = $me;
            }
            $to = array_unique($to);
        }

        $m_message = $this->getServiceMessage()->get($message_id);
        $res_user = $this->getServiceUser()->getLite($to);
        $ar_name = [];
        $owner = "";
        foreach ($res_user as $m_user) {
            $name = $owner = "";
            if(!is_object($m_user->getNickname()) &&  null !== $m_user->getNickname()) {
                $name = $m_user->getNickname();
                $owner = $name;
            } else {
                if(!is_object($m_user->getFirstname()) &&  null !== $m_user->getFirstname()) {
                    $name = $m_user->getFirstname();
                    $owner = $name;
                }
                if(!is_object($m_user->getLastname()) &&  null !== $m_user->getLastname()) {
                    $name .= ' '.$m_user->getLastname();
                }
            }
            
            if($m_user->getId() === $me) {
                $owner = $name;
            } else {
                $ar_name[] = $name;
            }
        }
        
        foreach ($to as $user) {
            $m_message_user = $this->getModel()
                ->setMessageId($message_id)
                ->setConversationId($conversation_id)
                ->setFromId($me)
                ->setUserId($user)
                ->setType((($user == $me) ? (($for_me) ? 'RS' : 'S') : 'R'))
                ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));

            if ($me == $user && !$for_me) {
                $m_message_user->setReadDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
            }

            if ($this->getMapper()->insert($m_message_user) <= 0) {
                throw new \Exception('error insert message to');
            }
            
            $gcmu = $this->getServiceGcmGroup()->getNotificationKey('user'.$user);
            if ($gcmu !== false) {
                $gcm_notification = new GcmNotification();
                $gcm_notification->setTitle(implode(", ", $ar_name))
                    ->setSound("default")
                    ->setColor("#00A38B")
                    ->setTag("CONV".$conversation_id)
                    ->setBody($owner. ": " .$m_message->getText());
                
                $gcm_message = new GcmMessage();
                $gcm_message->setTo($gcmu)
                    ->setNotification($gcm_notification)
                    ->setData([
                        'type' => 'message',
                        'users' => $to,
                        'from' => $me,
                        'conversation' => $conversation_id,
                        'text' => $m_message->getText()
                    ]);
                
                try {
                    $message = $this->getServiceGcmClient()->send($gcm_message);
                } catch (\Exception $e) {
                    syslog(1, "error fcm: ".$e->getMessage());
                }
            }
        }

        return $this->getMapper()->getLastInsertValue();
    }

    /**
     * Get List MessasgeUser.
     * 
     * @param int    $user_id
     * @param int    $message_id
     * @param int    $conversation_id
     * @param array  $filter
     * @param string $tag
     * @param string $type
     * @param string $search
     *
     * @return array
     */
    public function getList($user_id, $message_id = null, $conversation_id = null, $filter = null, $tag = null, $type = null, $search = null)
    {
        $mapper = $this->getMapper();
        $res_message_user = $mapper->usePaginator($filter)->getList($user_id, $message_id, $conversation_id, $tag, $type, $filter, $search);
        foreach ($res_message_user as $m_message_user) {
            $m_message_user->getMessage()->setTo($this->getServiceUser()->getList(null, null, null, null, null, null, null, null, false, null, null, null, ['R', $m_message_user->getMessage()->getId()])['list']);
            $m_message_user->getMessage()->setFrom($this->getServiceUser()->getList(null, null, null, null, null, null, null, null, false, null, null, null, ['S', $m_message_user->getMessage()->getId()])['list']); 
            $d = $this->getServiceMessageDoc()->getList($m_message_user->getMessage()->getId());
            $m_message_user->getMessage()->setDocument((count($d) !== 0) ? $d : []);
        }
        $res_message_user->rewind();

        return ['list' => $res_message_user, 'count' => $mapper->count()];
    }
    
    /**
     * 
     * @param int $user_id
     * @param int $conversation_id
     * 
     * @return \Dal\Db\ResultSet\
     */
    public function getListLastMessage($filter = null, $conversation_id = null)
    {
        $mapper = (null !== $filter) ? 
            $this->getMapper()->usePaginator($filter) : 
            $this->getMapper();
        
        $res_message_user = $mapper->getListLastMessage($this->getServiceUser()->getIdentity()['id'], $conversation_id);
        foreach ($res_message_user as $m_message_user) {
            $d = $this->getServiceMessageDoc()->getList($m_message_user->getMessage()->getId());
            $m_message_user->getMessage()->setDocument((count($d) !== 0) ? $d :[]);
        }
        
        return $res_message_user;
    }

    /**
     * Get MessasgeUser By Message id.
     * 
     * @param int $message_id
     *
     * @return \Application\Model\MessageUser
     */
    public function getMessage($message_id)
    {
        return $this->getList($this->getServiceUser()
            ->getIdentity()['id'], $message_id)['list']->current();
    }

    /**
     * Count number of tag.
     * 
     * @param string $tag
     * @param int    $type
     *
     * @return int
     */
    public function countTag($tag, $type)
    {
        return $this->getMapper()
            ->countTag($this->getServiceUser()
            ->getIdentity()['id'], $tag, $type)
            ->count();
    }

    /**
     * Mark read Message User by message id.
     *
     * @param int|array $conversation_id
     *
     * @return int
     */
    public function readByMessage($message_id)
    {
        $user_id = $this->getServiceUser()->getIdentity()['id'];

        if (!is_array($message_id)) {
            $message_id = [$message_id];
        }

        $m_message_user = $this->getModel()->setReadDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));

        return $this->getMapper()->update($m_message_user, array('message_id' => $message_id, 'user_id' => $user_id, new IsNull('read_date')));
    }

    /**
     * Mark UnRead Message User by message id.
     *
     * @param int|array $message_id
     *
     * @return int
     */
    public function UnReadByMessage($message_id)
    {
        $me = $this->getServiceUser()->getIdentity()['id'];

        if (!is_array($message_id)) {
            $message_id = [$message_id];
        }

        $m_message_user = $this->getModel()->setReadDate(new IsNull('read_date'));

        return $this->getMapper()->update($m_message_user, array('message_id' => $message_id, 'user_id' => $me, new IsNotNull('read_date')));
    }

    /**
     * Hard delete MessageUser by message id.
     *
     * @param int $message_id
     *
     * @return int
     */
    public function hardDeleteByMessage($message_id)
    {
        return $this->getMapper()->delete($this->getModel()
            ->setMessageId($message_id));
    }

    /**
     * Mark delete MessageUser by message.
     *
     * @param int|array $message_id
     *
     * @return int
     */
    public function deleteByMessage($message_id)
    {
        $me = $this->getServiceUser()->getIdentity()['id'];

        if (!is_array($message_id)) {
            $message_id = [$message_id];
        }

        $m_message_user = $this->getModel()->setDeletedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));

        return $this->getMapper()->update($m_message_user, array('message_id' => $message_id, 'user_id' => $me, new IsNull('deleted_date')));
    }

    /**
     * Mark delete MessageUser by conversation id.
     *
     * @param int|array $conversation_id
     *
     * @return int
     */
    public function deleteByConversation($conversation_id)
    {
        $me = $this->getServiceUser()->getIdentity()['id'];

        if (!is_array($conversation_id)) {
            $conversation_id = [$conversation_id];
        }

        $m_message_user = $this->getModel()->setDeletedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));

        return $this->getMapper()->update($m_message_user, array('conversation_id' => $conversation_id, 'user_id' => $me, new IsNull('deleted_date')));
    }

    /**
     * Mark read Message User by conversation.
     *
     * @param int|array $conversation_id
     *
     * @return int
     */
    public function readByConversation($conversation_id)
    {
        $me = $this->getServiceUser()->getIdentity()['id'];

        if (!is_array($conversation_id)) {
            $conversation_id = [$conversation_id];
        }

        $m_message_user = $this->getModel()->setReadDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));

        return $this->getMapper()->update($m_message_user, array('conversation_id' => $conversation_id, 'user_id' => $me, new IsNull('read_date')));
    }

    /**
     * Mark UnRead Message User by conversation.
     *
     * @param int|array $conversation_id
     *
     * @return int
     */
    public function unReadByConversation($conversation_id)
    {
        $me = $this->getServiceUser()->getIdentity()['id'];

        if (!is_array($conversation_id)) {
            $conversation_id = [$conversation_id];
        }

        $m_message_user = $this->getModel()->setReadDate(new IsNull());

        return $this->getMapper()->update($m_message_user, array('conversation_id' => $conversation_id, 'user_id' => $me, new IsNotNull('read_date')));
    }

    /**
     * Get Service Service User.
     *
     * @return \Application\Service\User
     */
    private function getServiceUser()
    {
        return $this->container->get('app_service_user');
    }

    /**
     * Get Service Service Message Document.
     *
     * @return \Application\Service\MessageDoc
     */
    private function getServiceMessageDoc()
    {
        return $this->container->get('app_service_message_doc');
    }
    
    /**
     * Get Service Service Message
     *
     * @return \Application\Service\Message
     */
    private function getServiceMessage()
    {
        return $this->container->get('app_service_message');
    }

    /**
     *
     * @return \ZendService\Google\Gcm\Client
     */
    private function getServiceGcmClient()
    {
        return $this->container->get('gcm-client');
    }
    
    /**
     * @return \Application\Service\GcmGroup
     */
    private function getServiceGcmGroup()
    {
        return $this->container->get('app_service_gcm_group');
    }
    
    /**
     * Get Service Service Conversation User.
     *
     * @return \Application\Service\ConversationUser
     */
    private function getServiceConversationUser()
    {
        return $this->container->get('app_service_conversation_user');
    }
}
