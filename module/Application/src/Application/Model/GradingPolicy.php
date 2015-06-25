<?php

namespace Application\Model;

use Application\Model\Base\GradingPolicy as BaseGradingPolicy;

class GradingPolicy extends BaseGradingPolicy
{
	protected $items;
	protected $nbr_comment;
	
	public function getItems()
	{
		return $this->items;
	}
	
	public function setItems($items)
	{
		$this->items = $items;
		
		return $this;
	}
	
	public function getNbrComment()
	{
		return $this->nbr_comment;
	}
	
	public function setNbrComment($nbr_comment)
	{
		$this->nbr_comment = $nbr_comment;
		 
		return $this;
	}
}
