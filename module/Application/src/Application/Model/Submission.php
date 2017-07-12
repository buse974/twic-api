<?php

namespace Application\Model;

use Application\Model\Base\Submission as BaseSubmission;

class Submission extends BaseSubmission
{
    
    protected $item_users;
    
    public function getItemUsers()
    {
        return $this->item_users;
    }

    public function setItemUsers($item_users)
    {
        $this->item_users = $item_users;

        return $this;
    }
}