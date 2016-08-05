<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class OrganizationUser extends AbstractModel
{
 	protected $organization_id;
	protected $user_id;

	protected $prefix = 'organization_user';

	public function getOrganizationId()
	{
		return $this->organization_id;
	}

	public function setOrganizationId($organization_id)
	{
		$this->organization_id = $organization_id;

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

}