<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class CtDone extends AbstractModel
{
 	protected $item_id;
	protected $target_id;
	protected $all;

	protected $prefix = 'ct_done';

	public function getItemId()
	{
		return $this->item_id;
	}

	public function setItemId($item_id)
	{
		$this->item_id = $item_id;

		return $this;
	}

	public function getTargetId()
	{
		return $this->target_id;
	}

	public function setTargetId($target_id)
	{
		$this->target_id = $target_id;

		return $this;
	}

	public function getAll()
	{
		return $this->all;
	}

	public function setAll($all)
	{
		$this->all = $all;

		return $this;
	}

}