<?php

namespace Application\Model;

use Application\Model\Base\Activity as BaseActivity;

class Activity extends BaseActivity
{
    protected $count;
    protected $linkedin_id;

    public function getCount()
    {
        return $this->count;
    }

    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }
    
     public function getLinkedinId()
    {
        return $this->linkedin_id;
    }

    public function setLinkedinId($linkedin_id)
    {
        $this->linkedin_id = $linkedin_id;

        return $this;
    }
}
