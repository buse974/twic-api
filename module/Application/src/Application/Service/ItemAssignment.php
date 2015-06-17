<?php

namespace Application\Service;

use Dal\Service\AbstractService;
use Application\Model\Item;

class ItemAssignment extends AbstractService
{
	/**
	 * @invokable
	 * 
	 * @param integer $item_prog
	 * @param string $type
	 * @param string $response
	 * @param array $documents
	 */
	public function add($item_prog, $type,$response = null, $documents = null)
	{
		$m_item_prog = $this->getModel()->setItemProgId($item_prog)
		                                ->setResponse($response);

		if($this->getMapper()->insert($m_item_prog) <= 0) {
			throw new \Exception('error insert item prog');
		}
		
		$item_assigment_id = $this->getMapper()->getLastInsertValue();
		if(is_array($documents)) {
			foreach ($documents as $d) {
				$type = isset($d['type']) ? $d['type'] : null;
				$title = isset($d['title']) ? $d['title'] : null;
				$author = isset($d['author']) ? $d['author'] : null;
				$link = isset($d['link']) ? $d['link'] : null;
				$source = isset($d['source']) ? $d['source'] : null;
				$token = isset($d['token']) ? $d['token'] : null;
				$date = isset($d['date']) ? $d['date'] : null;
				
				$this->getServiceItemAssignmentDocument()->add($item_assigment_id, $type, $title, $author, $link, $source, $token, $date);
			}
		}
		
		switch ($type) {
			case Item::TYPE_LIVE_CLASS:
			
			break;
			
			case (Item::TYPE_INDIVIDUAL_ASSIGMENT || Item::TYPE_CAPSTONE_PROJECT):
				
			break;
				
			case Item::TYPE_WORKGROUP:
				
			break;
		}
		
		return $item_assigment_id;
	}
	
	public function deleteByItemProg($item_prog)
	{
		$res_item_assignment = $this->getMapper()->select($this->getModel()->setItemProgId($item_prog));
		
		foreach ($res_item_assignment as $m_item_assignment) {
			$this->getServiceItemAssignmentDocument()->deleteByItemAssignment($m_item_assignment->getId());
		}
		
		return $this->getMapper()->delete($this->getModel()->setItemProgId($item_prog));
	}
	
	/**
	 * @return \Application\Service\ItemAssigmentDocument
	 */
	public function getServiceItemAssignmentDocument()
	{
		return $this->getServiceLocator()->get('app_service_item_assigment_document');
	}
	
	/**
	 * @return \Application\Service\ItemAssignmentUser
	 */
	public function getServiceItemAssignmentUser()
	{
		return $this->getServiceLocator()->get('app_service_item_assigment_user');
	}
}