<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class SubmissionUserCriteria extends AbstractService
{
    
     /**
     * @invokable
     *
     * @param int $submission
     */
    public function getListBySubmission($submission)
    {
        
        return $this->getMapper()->select($this->getModel()->setSubmissionId($submission));
    }
}