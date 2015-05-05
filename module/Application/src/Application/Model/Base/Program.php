<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class Program extends AbstractModel
{
    protected $id;
    protected $name;
    protected $school_id;
    protected $level;
    protected $sis;
    protected $deleted_date;

    protected $prefix = 'program';

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getSchoolId()
    {
        return $this->school_id;
    }

    public function setSchoolId($school_id)
    {
        $this->school_id = $school_id;

        return $this;
    }

    public function getLevel()
    {
        return $this->level;
    }

    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    public function getSis()
    {
        return $this->sis;
    }

    public function setSis($sis)
    {
        $this->sis = $sis;

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
