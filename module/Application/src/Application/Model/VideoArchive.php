<?php

namespace Application\Model;

use Application\Model\Base\VideoArchive as BaseVideoArchive;

class VideoArchive extends BaseVideoArchive
{
    const FINISHED = 'finished';
    const ONGOING = 'ongoing';
    const NOTSTARTED = 'notstarted';
    
    const ARV_AVAILABLE = 'available';
    const ARV_EXPIRED = 'expired';
    const ARV_FAILED = 'failed';
    const ARV_STARTED = 'started';
    const ARV_STOPPED = 'stopped';
    const ARV_UPLOAD = 'uploaded';
    
    protected $submission_id;
    protected $conversation_user;
    
    public function setConversationUser($conversation_user)
    {
        $this->conversation_user = $conversation_user;
    
        return $this;
    }
    
    public function getConversationUser()
    {
        return $this->conversation_user;
    }
    
    public function setSubmissionId($submission_id)
    {
        $this->submission_id = $submission_id;
        
        return $this;
    }
    
    public function getSubmissionId()
    {   
        return $this->submission_id;
    }
}