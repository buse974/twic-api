<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class ItemMaterialDocumentRelation extends AbstractModel
{
    protected $item_id;
    protected $material_document_id;

    protected $prefix = 'item_material_document_relation';

    public function getItemId()
    {
        return $this->item_id;
    }

    public function setItemId($item_id)
    {
        $this->item_id = $item_id;

        return $this;
    }

    public function getMaterialDocumentId()
    {
        return $this->material_document_id;
    }

    public function setMaterialDocumentId($material_document_id)
    {
        $this->material_document_id = $material_document_id;

        return $this;
    }
}
