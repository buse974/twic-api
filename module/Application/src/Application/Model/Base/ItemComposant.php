<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class ItemComposant extends AbstractModel
{
 	protected $item_id;
	protected $has_eqcq;
	protected $has_conversation;

	protected $prefix = 'item_composant';

	public function getItemId()
	{
		return $this->item_id;
	}

	public function setItemId($item_id)
	{
		$this->item_id = $item_id;

		return $this;
	}

	public function getHasEqcq()
	{
		return $this->has_eqcq;
	}

	public function setHasEqcq($has_eqcq)
	{
		$this->has_eqcq = $has_eqcq;

		return $this;
	}

	public function getHasConversation()
	{
		return $this->has_conversation;
	}

	public function setHasConversation($has_conversation)
	{
		$this->has_conversation = $has_conversation;

		return $this;
	}

}