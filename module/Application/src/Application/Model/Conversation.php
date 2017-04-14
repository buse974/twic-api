<?php

namespace Application\Model;

use Application\Model\Base\Conversation as BaseConversation;

class Conversation extends BaseConversation
{
    const TYPE_CHANNEL = 1;
    const TYPE_CHAT = 2;

    const DEFAULT_NAME = 'Chat';

    protected $messages;
    protected $users;
    protected $message_user;
    protected $nb_unread;
    protected $nb_users;
    protected $message;

    public function exchangeArray(array &$data)
    {
        parent::exchangeArray($data);

        $this->message = $this->requireModel('app_model_message', $data);
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function setMessages($messages)
    {
        $this->messages = $messages;

        return $this;
    }

    public function getMessageUser()
    {
        return $this->message_user;
    }

    public function setMessageUser($message_user)
    {
        $this->message_user = $message_user;

        return $this;
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function setUsers($users)
    {
        $this->users = $users;

        return $this;
    }

    public function getNbUnread()
    {
        return $this->nb_unread;
    }

    public function setNbUnread($nb_unread)
    {
        $this->nb_unread = $nb_unread;

        return $this;
    }

    /**
     * Get the value of Nb Users
     *
     * @return mixed
     */
    public function getNbUsers()
    {
        return $this->nb_users;
    }

    /**
     * Set the value of Nb Users
     *
     * @param mixed nb_users
     *
     * @return self
     */
    public function setNbUsers($nb_users)
    {
        $this->nb_users = $nb_users;

        return $this;
    }

}
