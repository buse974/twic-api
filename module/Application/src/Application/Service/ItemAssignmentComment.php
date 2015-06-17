<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class ItemAssignmentComment extends AbstractService
{
	public function add($item_assignment_id, $text)
	{
		$m_item_assignment_comment = $this->getModel()->setItemAssignmentId($item_assignment_id)
		                                              ->setText($text)
		                                              ->setUserId($this->getServiceAuth()->getIdentity()->getId());
		
		
		$this->getMapper()->insert($m_item_assignment_comment);
		                
	}
	
	/**
	 * @return \Zend\Authentication\AuthenticationService
	 */
	public function getServiceAuth()
	{
		return $this->getServiceLocator()->get('auth.service');
	}
}