<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class ConversationOpt extends AbstractModel
{
    protected $id;
    protected $item_id;
    protected $record;
    protected $nb_user_autorecord;
    protected $allow_intructor;
    protected $has_eqcq;
    protected $start_date;
    protected $duration;
    protected $rules;

    protected $prefix = 'conversation_opt';

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getItemId()
    {
        return $this->item_id;
    }

    public function setItemId($item_id)
    {
        $this->item_id = $item_id;

        return $this;
    }

    public function getRecord()
    {
        return $this->record;
    }

    public function setRecord($record)
    {
        $this->record = $record;

        return $this;
    }

    public function getNbUserAutorecord()
    {
        return $this->nb_user_autorecord;
    }

    public function setNbUserAutorecord($nb_user_autorecord)
    {
        $this->nb_user_autorecord = $nb_user_autorecord;

        return $this;
    }

    public function getAllowIntructor()
    {
        return $this->allow_intructor;
    }

    public function setAllowIntructor($allow_intructor)
    {
        $this->allow_intructor = $allow_intructor;

        return $this;
    }

    public function getHasEqcq()
    {
        return $this->has_eqcq;
    }

    public function setHasEqcq($has_eqcq)
    {
        $this->has_eqcq = $has_eqcq;

        return $this;
    }

    public function getStartDate()
    {
        return $this->start_date;
    }

    public function setStartDate($start_date)
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getDuration()
    {
        return $this->duration;
    }

    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    public function getRules()
    {
        return $this->rules;
    }

    public function setRules($rules)
    {
        $this->rules = $rules;

        return $this;
    }
}
