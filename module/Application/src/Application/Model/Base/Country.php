<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class Country extends AbstractModel
{
    protected $id;
    protected $short_name;

    protected $prefix = 'country';

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getShortName()
    {
        return $this->short_name;
    }

    public function setShortName($short_name)
    {
        $this->short_name = $short_name;

        return $this;
    }
}
