<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class ItemGrading extends AbstractService
{
    public function _getList()
    {
        return $this->getMapper()->getList();
    }
}
