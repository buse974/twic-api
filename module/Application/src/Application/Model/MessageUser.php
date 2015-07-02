<?php

namespace Application\Model;

use Application\Model\Base\MessageUser as BaseMessageUser;

class MessageUser extends BaseMessageUser
{
	protected $message;
	protected $user;
	
	public function exchangeArray(array &$data)
	{
		parent::exchangeArray($data);
	
		$this->message = new Message($this);
		$this->user = new User($this, 'from');
	
		$this->user->exchangeArray($data);
		$this->message->exchangeArray($data);
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
	
	public function setUser($user)
	{
		$this->user = $user;
	
		return $this;
	}
	
	public function getUser()
	{
		return $this->user;
	}
}
