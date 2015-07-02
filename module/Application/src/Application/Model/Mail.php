<?php

namespace Application\Model;

use Application\Model\Base\Mail as BaseMail;

class Mail extends BaseMail
{
	const TYPE_DRAFT = 'draft';
	const TYPE_SENT = 'sent';
	const TYPE_UNREAD = 'unread';
	const TYPE_DELETE = 'delete';
	
	protected $receiver;
	protected $mail_user;
	protected $mail_document;
	
	public function exchangeArray(array &$data)
	{
		parent::exchangeArray($data);
	
		$this->mail_user = new MailUser($this);
		$this->mail_user->exchangeArray($data);
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
	
	public function setMailUser($mail_user)
	{
		$this->mail_user = $mail_user;
	
		return $this;
	}
	
	public function getMailUser()
	{
		return $this->mail_user;
	}
	
	public function setMailDocument($mail_document)
	{
		$this->mail_document = $mail_document;
	
		return $this;
	}
	
	public function getMailDocument()
	{
		return $this->mail_document;
	}
}