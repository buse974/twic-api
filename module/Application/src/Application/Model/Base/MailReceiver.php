<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class MailReceiver extends AbstractModel
{
 	protected $id;
	protected $type;
	protected $user_id;
	protected $mail_id;

	protected $prefix = 'mail_receiver';

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	public function getType()
	{
		return $this->type;
	}

	public function setType($type)
	{
		$this->type = $type;

		return $this;
	}

	public function getUserId()
	{
		return $this->user_id;
	}

	public function setUserId($user_id)
	{
		$this->user_id = $user_id;

		return $this;
	}

	public function getMailId()
	{
		return $this->mail_id;
	}

	public function setMailId($mail_id)
	{
		$this->mail_id = $mail_id;

		return $this;
	}

}