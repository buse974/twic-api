<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class ItemAssignmentUser extends AbstractModel
{
    protected $item_assignment_id;
    protected $user_id;

    protected $prefix = 'item_assignment_user';

    public function getItemAssignmentId()
    {
        return $this->item_assignment_id;
    }

    public function setItemAssignmentId($item_assignment_id)
    {
        $this->item_assignment_id = $item_assignment_id;

        return $this;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }
}
