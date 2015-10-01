<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class Question extends AbstractModel
{
    protected $id;
    protected $text;
    protected $component_id;
    protected $created_date;
    protected $deleted_date;

    protected $prefix = 'question';

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    public function getComponentId()
    {
        return $this->component_id;
    }

    public function setComponentId($component_id)
    {
        $this->component_id = $component_id;

        return $this;
    }

    public function getCreatedDate()
    {
        return $this->created_date;
    }

    public function setCreatedDate($created_date)
    {
        $this->created_date = $created_date;

        return $this;
    }

    public function getDeletedDate()
    {
        return $this->deleted_date;
    }

    public function setDeletedDate($deleted_date)
    {
        $this->deleted_date = $deleted_date;

        return $this;
    }
}
