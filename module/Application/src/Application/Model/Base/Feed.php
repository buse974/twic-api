<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class Feed extends AbstractModel
{
 	protected $id;
	protected $content;
	protected $user_id;
	protected $link;
	protected $video;
	protected $picture;
	protected $name_picture;
	protected $document;
	protected $name_document;
	protected $link_title;
	protected $link_desc;
	protected $created_date;
	protected $deleted_date;
	protected $type;

	protected $prefix = 'feed';

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	public function getContent()
	{
		return $this->content;
	}

	public function setContent($content)
	{
		$this->content = $content;

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

	public function getLink()
	{
		return $this->link;
	}

	public function setLink($link)
	{
		$this->link = $link;

		return $this;
	}

	public function getVideo()
	{
		return $this->video;
	}

	public function setVideo($video)
	{
		$this->video = $video;

		return $this;
	}

	public function getPicture()
	{
		return $this->picture;
	}

	public function setPicture($picture)
	{
		$this->picture = $picture;

		return $this;
	}

	public function getNamePicture()
	{
		return $this->name_picture;
	}

	public function setNamePicture($name_picture)
	{
		$this->name_picture = $name_picture;

		return $this;
	}

	public function getDocument()
	{
		return $this->document;
	}

	public function setDocument($document)
	{
		$this->document = $document;

		return $this;
	}

	public function getNameDocument()
	{
		return $this->name_document;
	}

	public function setNameDocument($name_document)
	{
		$this->name_document = $name_document;

		return $this;
	}

	public function getLinkTitle()
	{
		return $this->link_title;
	}

	public function setLinkTitle($link_title)
	{
		$this->link_title = $link_title;

		return $this;
	}

	public function getLinkDesc()
	{
		return $this->link_desc;
	}

	public function setLinkDesc($link_desc)
	{
		$this->link_desc = $link_desc;

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

	public function getType()
	{
		return $this->type;
	}

	public function setType($type)
	{
		$this->type = $type;

		return $this;
	}

}