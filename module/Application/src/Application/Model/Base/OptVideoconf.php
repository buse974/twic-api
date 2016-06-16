<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class OptVideoconf extends AbstractModel
{
    protected $item_id;
    protected $record;
    protected $nb_user_autorecord;
    protected $allow_intructor;

    protected $prefix = 'opt_videoconf';

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
}
