<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class School extends AbstractModel
{
 	protected $id;
	protected $name;
	protected $next_name;
	protected $short_name;
	protected $logo;
	protected $describe;
	protected $website;
	protected $background;
	protected $phone;
	protected $contact;
	protected $contact_id;
	protected $address_id;
	protected $deleted_date;

	protected $prefix = 'school';

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}

	public function getNextName()
	{
		return $this->next_name;
	}

	public function setNextName($next_name)
	{
		$this->next_name = $next_name;

		return $this;
	}

	public function getShortName()
	{
		return $this->short_name;
	}

	public function setShortName($short_name)
	{
		$this->short_name = $short_name;

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

	public function getDescribe()
	{
		return $this->describe;
	}

	public function setDescribe($describe)
	{
		$this->describe = $describe;

		return $this;
	}

	public function getWebsite()
	{
		return $this->website;
	}

	public function setWebsite($website)
	{
		$this->website = $website;

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

	public function getPhone()
	{
		return $this->phone;
	}

	public function setPhone($phone)
	{
		$this->phone = $phone;

		return $this;
	}

	public function getContact()
	{
		return $this->contact;
	}

	public function setContact($contact)
	{
		$this->contact = $contact;

		return $this;
	}

	public function getContactId()
	{
		return $this->contact_id;
	}

	public function setContactId($contact_id)
	{
		$this->contact_id = $contact_id;

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

	public function getDeletedDate()
	{
		return $this->deleted_date;
	}

	public function setDeletedDate($deleted_date)
	{
		$this->deleted_date = $deleted_date;

		return $this;
	}

}