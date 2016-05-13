<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class ItemAssignmentRelation extends AbstractModel
{
    protected $item_assignment_id;
    protected $submission_user_id;

    protected $prefix = 'item_assignment_relation';

    public function getItemAssignmentId()
    {
        return $this->item_assignment_id;
    }

    public function setItemAssignmentId($item_assignment_id)
    {
        $this->item_assignment_id = $item_assignment_id;

        return $this;
    }

    public function getItemProgUserId()
    {
        return $this->submission_user_id;
    }

    public function setItemProgUserId($submission_user_id)
    {
        $this->submission_user_id = $submission_user_id;

        return $this;
    }
}
