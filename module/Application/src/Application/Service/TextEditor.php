<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class TextEditor extends AbstractService
{
    /**
     * @param int $submission_id
     *
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

    public function getListBySubmission($submission_id)
    {
        return $this->getMapper()->select($this->getModel()->setSubmissionId($submission_id));
    }

    public function getListBy($submission_id)
    {
        return $this->getMapper()->select($this->getModel()->setSubmissionId($submission_id));
    }

    /**
     * @invokable
     * 
     * @param int    $submission_id
     * @param string $name
     * @param string $text
     * @param string $submit_date
     * @param integer $conversation_id
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
     * @invokable
     *
     * @param int $id
     *
     * @return int
     */
    public function delete($id)
    {
        return $this->getMapper()->delete($this->getModel()->setId($id));
    }

    /**
     * @invokable
     * 
     * @param int    $id
     * @param int    $submission_id
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
