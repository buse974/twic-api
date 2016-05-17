<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class SubThread extends AbstractService
{
    /**
     * @param integer $thread_id
     * @param integer $submission_id
     * @return integer
     */
    public function add($thread_id, $submission_id)
    {
        $m_sub_tread = $this->getModel()->setThreadId($thread_id)->setSubmissionId($submission_id);
        
        if($this->getMapper()->select($m_sub_tread)->count() === 0) {
            return $this->getMapper()->insert($m_sub_tread);
        }
        
        return false;
    }
}