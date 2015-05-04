<?php

namespace Application\Model;

use Application\Model\Base\Message as BaseMessage;

class Message extends BaseMessage
{
    const TYPE_DRAFT  = 'draft';
    const TYPE_SENT   = 'sent';
    const TYPE_UNREAD = 'unread';
    const TYPE_DELETE = 'delete';

    protected $receiver;
    protected $message_user;
    protected $message_document;

    public function exchangeArray(array &$data)
    {
        parent::exchangeArray($data);

        $this->message_user = new MessageUser($this);
        $this->message_user->exchangeArray($data);
    }

    public function setReceiver($receiver)
    {
        $this->receiver = $receiver;

        return $this;
    }

    public function getReceiver()
    {
        return $this->receiver;
    }

    public function setMessageUser($message_user)
    {
        $this->message_user = $message_user;

        return $this;
    }

    public function getMessageUser()
    {
        return $this->message_user;
    }

    public function setMessageDocument($message_document)
    {
        $this->message_document = $message_document;

        return $this;
    }

    public function getMessageDocument()
    {
        return $this->message_document;
    }
}
