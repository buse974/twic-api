<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class Page extends AbstractModel
{
 	protected $id;
	protected $title;
	protected $logo;
	protected $background;
	protected $description;
	protected $confidentiality;
	protected $admission;
	protected $start_date;
	protected $end_date;
	protected $location;
	protected $type;
	protected $user_id;
	protected $organization_id;
	protected $page_id;
	protected $address_id;

	protected $prefix = 'page';

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function setTitle($title)
	{
		$this->title = $title;

		return $this;
	}

	public function getLogo()
	{
		return $this->logo;
	}

	public function setLogo($logo)
	{
		$this->logo = $logo;

		return $this;
	}

	public function getBackground()
	{
		return $this->background;
	}

	public function setBackground($background)
	{
		$this->background = $background;

		return $this;
	}

	public function getDescription()
	{
		return $this->description;
	}

	public function setDescription($description)
	{
		$this->description = $description;

		return $this;
	}

	public function getConfidentiality()
	{
		return $this->confidentiality;
	}

	public function setConfidentiality($confidentiality)
	{
		$this->confidentiality = $confidentiality;

		return $this;
	}

	public function getAdmission()
	{
		return $this->admission;
	}

	public function setAdmission($admission)
	{
		$this->admission = $admission;

		return $this;
	}

	public function getStartDate()
	{
		return $this->start_date;
	}

	public function setStartDate($start_date)
	{
		$this->start_date = $start_date;

		return $this;
	}

	public function getEndDate()
	{
		return $this->end_date;
	}

	public function setEndDate($end_date)
	{
		$this->end_date = $end_date;

		return $this;
	}

	public function getLocation()
	{
		return $this->location;
	}

	public function setLocation($location)
	{
		$this->location = $location;

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

	public function getOrganizationId()
	{
		return $this->organization_id;
	}

	public function setOrganizationId($organization_id)
	{
		$this->organization_id = $organization_id;

		return $this;
	}

	public function getPageId()
	{
		return $this->page_id;
	}

	public function setPageId($page_id)
	{
		$this->page_id = $page_id;

		return $this;
	}

	public function getAddressId()
	{
		return $this->address_id;
	}

	public function setAddressId($address_id)
	{
		$this->address_id = $address_id;

		return $this;
	}

}