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
        return $this->getMapper()->getList($submission);
    }
    
    
     /**
     * @invokable
     * 
     * @param integer $id
     */
    public function get($id) 
    {
        return $this->getMapper()->get($id)->current();
    }
    
    /**
     * @invokable
     * 
     * @param integer $submission_id
     * @param integer $user_id
     * @param string $file_name
     * @param string $file_token
     * @param string $audio
     * @param string $text
     */
    public function add($submission_id, $user_id, $file_name, $file_token, $audio, $text) 
    {
        return $this->get($this->getMapper()->insert(
            $this->getModel()->setSubmissionId($submission_id)
                ->setUserId($user_id)
                ->setFileName($file_name)
                ->setFileToken($file_token)
                ->setAudio($audio)
                ->setText($text)
        ));
    }
}