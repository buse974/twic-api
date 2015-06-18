<?php

namespace Application\Service;

use Dal\Service\AbstractService;
use \DateTime;
use \DateTimeZone;

class ItemGrading extends AbstractService
{
    public function _getList()
    {
        return $this->getMapper()->getList();
    }
    
    public function deleteByItemProgUser($item_prog_user)
    {
    	return $this->getMapper()->delete($this->getModel()->setItemProgUserId($item_prog_user));
    }
    
    public function add($item_prog_user, $grade)
    {
    	return $this->getMapper()->insert($this->getModel()
    			->setItemProgUserId($item_prog_user)
    			->setGrade($grade)
    			->setCreatedDate((new DateTime('now', new DateTimeZone('UTC')))->format('Y-m-d H:i:s'))
    			);
    }
}
