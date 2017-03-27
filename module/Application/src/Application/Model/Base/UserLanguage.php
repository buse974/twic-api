<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class UserLanguage extends AbstractModel
{
 	protected $id;
	protected $user_id;
	protected $language_id;
	protected $language_level_id;

	protected $prefix = 'user_language';

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;

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

	public function getLanguageId()
	{
		return $this->language_id;
	}

	public function setLanguageId($language_id)
	{
		$this->language_id = $language_id;

		return $this;
	}

	public function getLanguageLevelId()
	{
		return $this->language_level_id;
	}

	public function setLanguageLevelId($language_level_id)
	{
		$this->language_level_id = $language_level_id;

		return $this;
	}

}