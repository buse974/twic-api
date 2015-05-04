<?php

namespace Application\Model;

use Application\Model\Base\Module as BaseModule;

class Module extends BaseModule
{
    protected $module_assignments;
    protected $material_document;

    public function setModuleAssignments($module_assignments)
    {
        $this->module_assignments = $module_assignments;

        return $this;
    }

    public function getModuleAssignments()
    {
        return $this->module_assignments;
    }

    public function setMaterialDocument($material_document)
    {
        $this->material_document = $material_document;

        return $this;
    }

    public function getMaterialDocument()
    {
        return $this->material_document;
    }
}
