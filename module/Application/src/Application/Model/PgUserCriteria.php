<?php

namespace Application\Model;

use Application\Model\Base\PgUserCriteria as BasePgUserCriteria;

class PgUserCriteria extends BasePgUserCriteria
{
    protected $grade;

    public function exchangeArray(array &$data)
    {
        parent::exchangeArray($data);
    }

    public function getGrade()
    {
        return $this->grade;
    }

    public function setGrade($grade)
    {
        $this->grade = $grade;

        return $this;
    }
}
