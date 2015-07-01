<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class ModuleMaterialDocumentRelation extends AbstractMapper
{
    public function getListIdByModuleId($module)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('material_document', 'module_id'))
               ->where(array('module_id' => $module));

        return $this->selectWith($select);
    }
}
