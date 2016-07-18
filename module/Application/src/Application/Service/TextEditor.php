<?php
/**
 * 
 * TheStudnet (http://thestudnet.com)
 *
 * Text Editor
 *
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class TextEditor
 */
class TextEditor extends AbstractService
{

    /**
     * Get List Text Editor Or if Not exist Create
     *
     * @param int $submission_id            
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListOrCreate($submission_id)
    {
        $res_text_editor = $this->getListBySubmission($submission_id);
        if ($res_text_editor->count() <= 0) {
            $this->add($submission_id);
            $res_text_editor = $this->getListBySubmission($submission_id);
        }
        
        return $res_text_editor;
    }

    /**
     * Get List Text Editor
     *
     * @param int $submission_id            
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListBySubmission($submission_id)
    {
        return $this->getMapper()->select($this->getModel()
            ->setSubmissionId($submission_id));
    }

    /**
     * Get List Text Editor By Conversation
     *
     * @param int $conversation_id            
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListByConversation($conversation_id)
    {
        return $this->getMapper()->getListByConversation($conversation_id);
    }

    /**
     * Create Text Editor
     * 
     * @invokable
     *
     * @param int $submission_id            
     * @param string $name            
     * @param string $text            
     * @param string $submit_date            
     * @param int $conversation_id  
     * @return int          
     */
    public function add($submission_id, $name = null, $text = null, $submit_date = null, $conversation_id = null)
    {
        $m_text_editor = $this->getModel()
            ->setSubmissionId($submission_id)
            ->setName($name)
            ->setText($text)
            ->setSubmitDate($submit_date);
        
        if ($this->getMapper()->insert($m_text_editor) <= 0) {
            // @TODO error
        }
        
        return $this->getMapper()->getLastInsertValue();
    }

    /**
     * Create Text Editor
     * 
     * @param array $data
     * @return int
     */
    public function _add($data)
    {
        $submission_id = ((isset($data['submission_id'])) ? $data['submission_id'] : null);
        $name = ((isset($data['name'])) ? $data['name'] : null);
        $text = ((isset($data['text'])) ? $data['text'] : null);
        $submit_date = ((isset($data['submit_date'])) ? $data['submit_date'] : null);
        $conversation_id = ((isset($data['conversation_id'])) ? $data['conversation_id'] : null);
        
        return $this->add($submission_id, $name, $text, $submit_date, $conversation_id);
    }

    /**
     * Delete TextEditor
     *
     * @invokable
     *
     * @param int $id            
     * @return int
     */
    public function delete($id)
    {
        return $this->getMapper()->delete($this->getModel()
            ->setId($id));
    }

    /**
     * Update TextEditor
     *
     * @invokable
     *
     * @param int $id            
     * @param int $submission_id            
     * @param string $name            
     * @param string $text            
     * @param string $submit_date            
     * @return int
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
