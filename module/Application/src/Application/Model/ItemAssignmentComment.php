<?php

namespace Application\Model;

use Application\Model\Base\ItemAssignmentComment as BaseItemAssignmentComment;

class ItemAssignmentComment extends BaseItemAssignmentComment
{
    protected $user;

    public function exchangeArray(array &$data)
    {
        parent::exchangeArray($data);

        $this->user = $this->requireModel('app_model_user', $data);
    }

    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }
}
