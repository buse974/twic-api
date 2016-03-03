<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class CtDate extends AbstractModel
{
 	protected $item_id;
	protected $date;
	protected $AFTER;

	protected $prefix = 'ct_date';

	public function getItemId()
	{
		return $this->item_id;
	}

	public function setItemId($item_id)
	{
		$this->item_id = $item_id;

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

	public function getAFTER()
	{
		return $this->AFTER;
	}

	public function setAFTER($AFTER)
	{
		$this->AFTER = $AFTER;

		return $this;
	}

}