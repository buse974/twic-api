<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class ModuleMaterialDocumentRelation extends AbstractModel
{
    protected $module_id;
    protected $material_document;

    protected $prefix = 'module_material_document_relation';

    public function getModuleId()
    {
        return $this->module_id;
    }

    public function setModuleId($module_id)
    {
        $this->module_id = $module_id;

        return $this;
    }

    public function getMaterialDocument()
    {
        return $this->material_document;
    }

    public function setMaterialDocument($material_document)
    {
        $this->material_document = $material_document;

        return $this;
    }
}
