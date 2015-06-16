<?php

namespace Application\Service;

use Dal\Service\AbstractService;

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
}
