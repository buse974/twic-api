<?php
namespace Application\Service;

use Dal\Service\AbstractService;

class SubWhiteboard extends AbstractService
{

    /**
     * Add Relation
     *
     * @param int $submission_id            
     * @param int $whiteboard_id            
     * @return int
     */
    public function add($submission_id, $whiteboard_id)
    {
        $m_sub_whiteboard = $this->getModel()
            ->setWhiteboardId($whiteboard_id)
            ->setSubmissionId($submission_id);
        
        $res_sub_whiteboard = $this->getMapper()->select($m_sub_whiteboard);
        
        return ($res_sub_whiteboard->count() == 0) ? $this->getMapper()->insert($m_sub_whiteboard) : 0;
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