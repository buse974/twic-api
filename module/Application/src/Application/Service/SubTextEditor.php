<?php
/**
 *
 * TheStudnet (http://thestudnet.com)
 *
 * Submission Text Editor
 *
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class SubTextEditor
 */
class SubTextEditor extends AbstractService
{

    /**
     * Add Relation
     *
     * @param int $submission_id            
     * @param int $text_editor_id            
     * @return int
     */
    public function add($submission_id, $text_editor_id)
    {
        $m_sub_text_editor = $this->getModel()
            ->setSubmissionId($submission_id)
            ->setTextEditorId($text_editor_id);
        
        $res_sub_text_editor = $this->getMapper()->select($m_sub_text_editor);
        
        return ($res_sub_text_editor->count() == 0) ? $this->getMapper()->insert($m_sub_text_editor) : 0;
    }
    
    /**
     * Get List
     *
     * @param int $submission_id
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getList($submission_id)
    {
        return $this->getMapper()->select($this->getModel()
            ->setSubmissionId($submission_id));
    }
}