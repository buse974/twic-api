<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Bank Answer Item
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class BankAnswerItem.
 */
class BankAnswerItem extends AbstractService
{
    /**
     * Add Answer Item.
     *
     * @param int    $bank_question_item_id
     * @param int    $percent
     * @param string $answer
     *
     * @return int
     */
    public function add($bank_question_item_id, $percent, $answer)
    {
        $m_bank_answer_item = $this->getModel()
            ->setBankQuestionItemId($bank_question_item_id)
            ->setPercent($percent)
            ->setAnswer($answer);

        return $this->getMapper()->insert($m_bank_answer_item);
    }

    /**
     * Copy Answer Item.
     * 
     * @param int $bank_question_item_id_new
     * @param int $bank_question_item_id_old
     *
     * @return int
     */
    public function copy($bank_question_item_id_new, $bank_question_item_id_old)
    {
        $m_bank_answer_item = $this->getMapper()
            ->select($this->getModel()
            ->setBankQuestionItemId($bank_question_item_id_old))
            ->current();

        return $this->getMapper()->insert($m_bank_answer_item->setBankQuestionItemId($bank_question_item_id_new));
    }

    /**
     * Get Answer Item.
     * 
     * @param int $bank_question_item_id
     *
     * @return \Application\Model\BankAnswerItem|null
     */
    public function get($bank_question_item_id)
    {
        $res_bank_answer_item = $this->getMapper()->select($this->getModel()
            ->setBankQuestionItemId($bank_question_item_id));

        return ($res_bank_answer_item->count() > 0) ? $res_bank_answer_item->current() : null;
    }
}
