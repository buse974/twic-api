<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class SubmissionComments extends AbstractService
{
    /**
     * @invokable
     * 
     * @param integer $submission
     */
    public function getList($submission) 
    {
        return $this->getMapper()->select($this->getModel()->getSubmission());
    }
}