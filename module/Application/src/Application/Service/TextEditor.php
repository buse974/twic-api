<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class TextEditor extends AbstractService
{
    /**
     * 
     * @param integer $submission_id
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListOrCreate($submission_id)
    {
        $res_text_editor = $this->getListBySubmission($submission_id);
        if($res_text_editor->count() <= 0) {
            $this->add($submission_id);
            $res_text_editor = $this->getListBySubmission($submission_id);
        }
        
        return $res_text_editor;
    }
    
    public function getListBySubmission($submission_id)
    {
        return $this->getMapper()->select($this->getModel()->setSubmissionId($submission_id));
    }
    
    /**
     * @invokable
     * 
     * @param integer $submission_id
     * @param string $name
     * @param string $text
     * @param string $submit_date
     */
    public function add($submission_id, $name = null, $text = null, $submit_date = null) 
    {
        $m_text_editor = $this->getModel()
            ->setSubmissionId($submission_id)
            ->setName($name)
            ->setText($text)
            ->setSubmitDate($submit_date);
        
        if($this->getMapper()->insert($m_text_editor) <= 0) {
            // @TODO error
        }
        
        return $this->getMapper()->getLastInsertValue();
    }

    /**
     * @invokable
     * 
     * @param integer $id
     * @param integer $submission_id
     * @param string $name
     * @param string $text
     * @param string $submit_date
     */
    public function update($id, $submission_id = null, $name = null, $text = null, $submit_date = null)
    {
        $m_text_editor = $this->getModel()
            ->setId($id)
            ->setSubmissionId($submission_id)
            ->setName($name)
            ->setSubmitDate($submit_date)
            ->setText($text);
    
        return $this->getMapper()->update($m_text_editor);
    }
}