<?php

namespace Application\Model;

use Application\Model\Base\Activity as BaseActivity;

class Activity extends BaseActivity
{
    protected $user;
    protected $value_user;
    protected $value_total;

    
     public function exchangeArray(array &$data)
    {
        parent::exchangeArray($data);

        $this->user = $this->requireModel('app_model_user', $data);
    }

    
     public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }
    
    public function getValueTotal()
    {
        return $this->value_total;
    }

    public function setValueTotal($value_total)
    {
        $this->value_total = $value_total;

        return $this;
    }

    public function getValueUser()
    {
        return $this->value_user;
    }

    public function setValueUser($value_user)
    {
        $this->value_user = $value_user;

        return $this;
    }
}
