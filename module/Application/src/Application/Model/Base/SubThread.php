<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class SubThread extends AbstractModel
{
    protected $submission_id;
    protected $thread_id;

    protected $prefix = 'sub_thread';

    public function getSubmissionId()
    {
        return $this->submission_id;
    }

    public function setSubmissionId($submission_id)
    {
        $this->submission_id = $submission_id;

        return $this;
    }

    public function getThreadId()
    {
        return $this->thread_id;
    }

    public function setThreadId($thread_id)
    {
        $this->thread_id = $thread_id;

        return $this;
    }
}
