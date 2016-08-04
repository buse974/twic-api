<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Question Relation
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class QuestionRelation.
 */
class QuestionRelation extends AbstractService
{
    /**
     * Add QuestionRelation.
     * 
     * @param int $group_question_id
     * @param int $bank_question_id
     *
     * @return int
     */
    public function add($group_question_id, $bank_question_id)
    {
        return $this->getMapper()->insert($this->getModel()->setGroupQuestionId($group_question_id)->setBankQuestionId($bank_question_id));
    }

    /**
     * Get List QuestionRelation.
     * 
     * @param int $group_question_id
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getList($group_question_id)
    {
        return $this->getMapper()->select($this->getModel()->setGroupQuestionId($group_question_id));
    }
}
