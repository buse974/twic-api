<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class ItemAssigmentDocument extends AbstractModel
{
 	protected $id;
	protected $item_assigment_id;
	protected $type;
	protected $title;
	protected $author;
	protected $link;
	protected $source;
	protected $token;
	protected $date;
	protected $created_date;
	protected $deleted_date;
	protected $updated_date;

	protected $prefix = 'item_assigment_document';

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	public function getItemAssigmentId()
	{
		return $this->item_assigment_id;
	}

	public function setItemAssigmentId($item_assigment_id)
	{
		$this->item_assigment_id = $item_assigment_id;

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

	public function getTitle()
	{
		return $this->title;
	}

	public function setTitle($title)
	{
		$this->title = $title;

		return $this;
	}

	public function getAuthor()
	{
		return $this->author;
	}

	public function setAuthor($author)
	{
		$this->author = $author;

		return $this;
	}

	public function getLink()
	{
		return $this->link;
	}

	public function setLink($link)
	{
		$this->link = $link;

		return $this;
	}

	public function getSource()
	{
		return $this->source;
	}

	public function setSource($source)
	{
		$this->source = $source;

		return $this;
	}

	public function getToken()
	{
		return $this->token;
	}

	public function setToken($token)
	{
		$this->token = $token;

		return $this;
	}

	public function getDate()
	{
		return $this->date;
	}

	public function setDate($date)
	{
		$this->date = $date;

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

	public function getUpdatedDate()
	{
		return $this->updated_date;
	}

	public function setUpdatedDate($updated_date)
	{
		$this->updated_date = $updated_date;

		return $this;
	}

}