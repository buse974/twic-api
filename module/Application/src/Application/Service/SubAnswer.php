<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Submission Answer
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class SubAnswer.
 */
class SubAnswer extends AbstractService
{
    /**
     * Get List Lite.
     * 
     * @param int $sub_question_ids
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListLite($sub_question_ids)
    {
        return $this->getMapper()->select($this->getModel()->setSubQuestionId($sub_question_ids));
    }

    /**
     * Add Sub Answer.
     * 
     * @param int $sub_question_id
     * @param int $bank_question_item_id
     * @param int $answer
     *
     * @return int
     */
    public function add($sub_question_id, $bank_question_item_id, $answer)
    {
        return $this->getMapper()->insert($this->getModel()
            ->setSubQuestionId($sub_question_id)
            ->setAnswer($answer)
            ->setBankQuestionItemId($bank_question_item_id));
    }
}
