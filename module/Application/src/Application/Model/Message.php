<?php

namespace Application\Model;

use Application\Model\Base\Message as BaseMessage;

class Message extends BaseMessage
{
  protected $message_user;

  public function exchangeArray(array &$data)
  {
      parent::exchangeArray($data);

      $this->message_user = $this->requireModel('app_model_message_user', $data);
  }

    /**
     * Get the value of Message User
     *
     * @return mixed
     */
    public function getMessageUser()
    {
        return $this->message_user;
    }

    /**
     * Set the value of Message User
     *
     * @param mixed message_user
     *
     * @return self
     */
    public function setMessageUser($message_user)
    {
        $this->message_user = $message_user;

        return $this;
    }
}
