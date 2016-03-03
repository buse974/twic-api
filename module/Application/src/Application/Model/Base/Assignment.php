<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class Assignment extends AbstractModel
{
 	protected $id;
	protected $response;
	protected $item_id;
	protected $submit_date;

	protected $prefix = 'assignment';

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	public function getResponse()
	{
		return $this->response;
	}

	public function setResponse($response)
	{
		$this->response = $response;

		return $this;
	}

	public function getItemId()
	{
		return $this->item_id;
	}

	public function setItemId($item_id)
	{
		$this->item_id = $item_id;

		return $this;
	}

	public function getSubmitDate()
	{
		return $this->submit_date;
	}

	public function setSubmitDate($submit_date)
	{
		$this->submit_date = $submit_date;

		return $this;
	}

}