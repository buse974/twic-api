<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class WorkshopMaterialDocumentRelation extends AbstractModel
{
    protected $workshop_id;
    protected $material_document;

    protected $prefix = 'workshop_material_document_relation';

    public function getWorkshopId()
    {
        return $this->workshop_id;
    }

    public function setWorkshopId($workshop_id)
    {
        $this->workshop_id = $workshop_id;

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
