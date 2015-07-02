<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class MailUser extends AbstractModel
{
 	protected $id;
	protected $mail_id;
	protected $user_id;
	protected $mail_group_id;
	protected $created_date;
	protected $deleted_date;
	protected $read_date;

	protected $prefix = 'mail_user';

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;

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

	public function getUserId()
	{
		return $this->user_id;
	}

	public function setUserId($user_id)
	{
		$this->user_id = $user_id;

		return $this;
	}

	public function getMailGroupId()
	{
		return $this->mail_group_id;
	}

	public function setMailGroupId($mail_group_id)
	{
		$this->mail_group_id = $mail_group_id;

		return $this;
	}

	public function getCreatedDate()
	{
		return $this->created_date;
	}

	public function setCreatedDate($created_date)
	{
		$this->created_date = $created_date;

		return $this;
	}

	public function getDeletedDate()
	{
		return $this->deleted_date;
	}

	public function setDeletedDate($deleted_date)
	{
		$this->deleted_date = $deleted_date;

		return $this;
	}

	public function getReadDate()
	{
		return $this->read_date;
	}

	public function setReadDate($read_date)
	{
		$this->read_date = $read_date;

		return $this;
	}

}