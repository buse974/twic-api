<?php

namespace Application\Model;

use Application\Model\Base\GradingPolicy as BaseGradingPolicy;

class GradingPolicy extends BaseGradingPolicy
{
	protected $items;
	
	public function getItems()
	{
		return $this->items;
	}
	
	public function setItems($items)
	{
		$this->items = $items;
		
		return $this;
	}
}
