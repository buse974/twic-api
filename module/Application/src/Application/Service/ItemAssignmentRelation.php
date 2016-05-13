<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class ItemAssignmentRelation extends AbstractService
{
    public function add($submission_user, $item_assignment)
    {
        if (!is_array($submission_user)) {
            $submission_user = array($submission_user);
        }
        if (!is_array($item_assignment)) {
            $item_assignment = array($item_assignment);
        }
        foreach ($submission_user as $u) {
            foreach ($item_assignment as $ia) {
                $this->getMapper()->insert($this->getModel()->setItemProgUserId($u)->setItemAssignmentId($ia));
            }
        }
    }

    public function deleteByItemAssignment($item_assignment)
    {
        return $this->getMapper()->delete($this->getModel()->setItemAssignmentId($item_assignment));
    }

    public function getByItemAssignment($item_assignment)
    {
        return $this->getMapper()->select($this->getModel()->setItemAssignmentId($item_assignment));
    }
}
