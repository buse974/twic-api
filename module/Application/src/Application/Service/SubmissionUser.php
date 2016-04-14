<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class SubmissionUser extends AbstractService
{
    public function create($submission_id, array $users)
    {
        $ret = [];
        foreach ($users as $user) {
             $ret[$user] = $this->getMapper()->insert($this->getModel()->setSubmissionId($submission_id)->setUserId($user));
        }
        
        return $ret;
    }
}