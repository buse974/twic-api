<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Message
 */
namespace Application\Service;

use Dal\Service\AbstractService;
use Application\Model\Conversation as ModelConversation;

/**
 * Class Message.
 */
class Message extends AbstractService
{
    /**
     * Send message.
     *
     * @invokable
     *
     * @param string $text
     * @param int    $to
     * @param int    $conversation
     * @param int    $item
     */
    public function sendSubmission($text = null, $to = null, $conversation = null, $item = null)
    {
        return $this->_send($text, $to, $conversation, ModelConversation::TYPE_ITEM_GROUP_ASSIGNMENT, $item);
    }

    /**
     * Send message.
     *
     * @invokable
     *
     * @param string    $text
     * @param int|array $to
     * @param int       $conversation
     * @param int       $type
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function send($text = null, $to = null, $conversation = null, $type = null)
    {
        if ($conversation !== null && null === $type) {
            $m_conversation = $this->getServiceConversation()->getLite($conversation);
            $type = $m_conversation->getType();
        }
        if (!is_numeric($type)) {
            $type = ModelConversation::TYPE_CHAT;
        }

        return $this->_send($text, $to, $conversation, $type);
    }

    /**
     * Send Message For Mail.
     *
     * @invokable
     *
     * @param string    $title
     * @param string    $text
     * @param int|array $to
     * @param int       $conversation
     * @param bool      $draft
     * @param int       $id
     * @param array     $document
     *
     * @throws \Exception
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function sendMail($title, $text, $to, $conversation = null, $draft = false, $id = null, $document = null)
    {
        if (empty($to) && $draft === false && null === $conversation && null === $id) {
            throw new \Exception('error params, check $to  $draft $conversation and $id');
        }

        if (!is_array($to)) {
            $to = array($to);
        }

        // Fetches sender id
        $me = $this->getServiceUser()->getIdentity()['id'];

        // Id is set => update
        if (null !== $id) {
            $m_message = $this->get($id);
            $message_id = $m_message->getId();
            $conversation = $m_message->getConversationId();

            // Applies the changes and update
            $m_message = $this->getModel()
                ->setId($message_id)
                ->setTitle($title)
                ->setIsDraft($draft)
                ->setText($text)
                ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));

            $this->getMapper()->update($m_message);
        }

        // Id is not set => insert
        else {
            // Conversation is not set => create it and stores the conversation id
            if (null === $conversation) {
                $tmp = $to;
                if (!in_array($me, $tmp)) {
                    $tmp[] = $me;
                }
                $conversation = $this->getServiceConversation()->create(ModelConversation::TYPE_EMAIL, null, $tmp);
            }

            // Applies the params to a new model
            $m_message = $this->getModel()
                ->setTitle($title)
                ->setIsDraft($draft)
                ->setType(1)
                ->setText($text)
                ->setConversationId($conversation)
                ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));

            // Inserts it or throws an error
            if ($this->getMapper()->insert($m_message) <= 0) {
                throw new \Exception('error insert message');
            }
            // Stores the new message id
            $message_id = $this->getMapper()->getLastInsertValue();
        }

        $this->getServiceMessageDoc()->replace($message_id, $document);
        // Delete all users and inserts them again
        $this->getServiceMessageUser()->hardDeleteByMessage($message_id);
        $message_user_id = $this->getServiceMessageUser()->send($message_id, $conversation, $to);

        if ($draft === false) {
            $this->getServiceEvent()->messageNew($message_id, $to);
        }

        return $this->getServiceMessageUser()->getList($me, $message_id)['list']->current();
    }

    /**
     * Send message generique.
     *
     * @param string    $text
     * @param int|array $to
     * @param int       $conversation
     * @param int       $type
     * @param int       $item
     *
     * @throws \Exception
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function _send($text = null, $to = null, $conversation = null, $type = null, $item = null)
    {
        $me = $this->getServiceUser()->getIdentity()['id'];

        if (null !== $to && $conversation === null) {
            if (!is_array($to)) {
                $to = array($to);
            }
            if (!in_array($me, $to)) {
                $to[] = $me;
            }
            $conversation = $this->getServiceConversationUser()->getConversationByUser($to, $type, $item);
        } elseif ($conversation !== null) {
            if (!$this->getServiceConversationUser()->isInConversation($conversation, $me)) {
                throw new \Exception('User '.$me.' is not in conversation '.$conversation);
            }
        }

        if (empty($text)) {
            throw new \Exception('error content is empty');
        }

        $m_message = $this->getModel()
            ->setText($text)
            ->setType($type)
            ->setConversationId($conversation)
            ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));

        if ($this->getMapper()->insert($m_message) <= 0) {
            throw new \Exception('error insert message');
        }

        $message_id = $this->getMapper()->getLastInsertValue();
        $message_user_id = $this->getServiceMessageUser()->send($message_id, $conversation);

        return $this->getServiceMessageUser()->getList($me, $message_id)['list']->current();
    }

    /**
     * Get List By user Conversation.
     *
     * @invokable
     *
     * @param int   $conversation
     * @param array $filter
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getList($conversation, $filter = [])
    {
        $user_id = $this->getServiceUser()->getIdentity()['id'];

        return $this->getServiceMessageUser()->getList($user_id, null, $conversation, $filter);
    }

    /**
     * Get List Tag with number message by tag.
     *
     * @invokable
     *
     * @return array
     */
    public function getListTag()
    {
        return [['tag' => 'INBOX', 'count' => $this->getServiceMessageUser()->countTag('NOREAD', 1)], ['tag' => 'SENT', 'count' => 0], ['tag' => 'DRAFT', 'count' => $this->getServiceMessageUser()->countTag('DRAFT', 1)], ['tag' => 'CHAT', 'count' => $this->getServiceMessageUser()->countTag('NOREAD', [2, 3])]];
    }

    /**
     * Read Message(s).
     *
     * @invokable
     *
     * @param int|array $message
     *
     * @return int
     */
    public function read($message)
    {
        return $this->getServiceMessageUser()->readByMessage($message);
    }

    /**
     * UnRead Message(s).
     *
     * @invokable
     *
     * @param int|array $message
     *
     * @return int
     */
    public function unRead($message)
    {
        return $this->getServiceMessageUser()->UnReadByMessage($message);
    }

    /**
     * Delete Message(s).
     *
     * @invokable
     *
     * @param int|array $id
     *
     * @return int
     */
    public function delete($id)
    {
        return $this->getServiceMessageUser()->deleteByMessage($id);
    }

    /**
     * Get List Conversation.
     *
     * @invokable
     *
     * @param string $filter
     * @param string $tag
     * @param int    $type
     * @param string $search
     *
     * @return array
     */
    public function getListConversation($filter = null, $tag = null, $type = null, $search = null)
    {
        return $this->getServiceMessageUser()->getList($this->getServiceUser()
            ->getIdentity()['id'], null, null, $filter, $tag, $type, $search);
    }

    /**
     * Get Message.
     *
     * @param int $id
     *
     * @return null|\Application\Model\Message
     */
    public function get($id)
    {
        $res_message = $this->getMapper()->select($this->getModel()
            ->setId($id));
        if ($res_message->count() <= 0) {
            throw new \Exception('error select message with id :'.$id);
        }

        return $res_message->current();
    }

    /**
     * Get All Message Conversation.
     *
     * @invokable
     *
     * @param int $conversation_id
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getFullList($conversation_id)
    {
        return $this->getMapper()->getFullList($conversation_id);
    }

    /**
     * Get number message.
     *
     * @invokable
     *
     * @param int $school
     *
     * @return array
     */
    public function getNbrMessage($school)
    {
        return ['d' => $this->getMapper()->getNbrMessage($school, 1), 'w' => $this->getMapper()->getNbrMessage($school, 7), 'm' => $this->getMapper()->getNbrMessage($school, 30), 'a' => $this->getMapper()->getNbrMessage($school)];
    }

    /**
     * Get Service User.
     *
     * @return \Application\Service\User
     */
    private function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }

    /**
     * Get Service MessageUser.
     *
     * @return \Application\Service\MessageUser
     */
    private function getServiceMessageUser()
    {
        return $this->getServiceLocator()->get('app_service_message_user');
    }

    /**
     * Get Service MessageDoc.
     *
     * @return \Application\Service\MessageDoc
     */
    private function getServiceMessageDoc()
    {
        return $this->getServiceLocator()->get('app_service_message_doc');
    }

    /**
     * Get Service ConversationUser.
     *
     * @return \Application\Service\ConversationUser
     */
    private function getServiceConversationUser()
    {
        return $this->getServiceLocator()->get('app_service_conversation_user');
    }

    /**
     * Get Service Conversation.
     *
     * @return \Application\Service\Conversation
     */
    private function getServiceConversation()
    {
        return $this->getServiceLocator()->get('app_service_conversation');
    }

    /**
     * Get Service Event.
     *
     * @return \Application\Service\Event
     */
    private function getServiceEvent()
    {
        return $this->getServiceLocator()->get('app_service_event');
    }
}
