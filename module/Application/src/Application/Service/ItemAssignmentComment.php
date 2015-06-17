<?php

namespace Application\Service;

use Dal\Service\AbstractService;
use \DateTime;
use \DateTimeZone;

class ItemAssignmentComment extends AbstractService
{
	public function add($item_assignment_id, $text)
	{
		$m_item_assignment_comment = $this->getModel()->setItemAssignmentId($item_assignment_id)
		                                              ->setText($text)
		                                              ->setUserId($this->getServiceAuth()->getIdentity()->getId())
		                                              ->setCreatedDate((new DateTime('now', new DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
		
		
		return $this->getMapper()->insert($m_item_assignment_comment);
		                
	}
	
	public function deleteByItemAssignment($item_assignment)
	{
		return $this->getMapper()->delete($this->getModel()->setItemAssignmentId($item_assignment));
	}
	
	/**
	 * @return \Zend\Authentication\AuthenticationService
	 */
	public function getServiceAuth()
	{
		return $this->getServiceLocator()->get('auth.service');
	}
}