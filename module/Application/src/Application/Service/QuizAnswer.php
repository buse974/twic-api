<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class QuizAnswer extends AbstractService
{
  /**
   * Add Quiz Answer
   *
   * @param  string $quiz_question_id
   * @param  array $answers
   *
   * @return int
   */
  public function add($quiz_question_id, $answers)
  {
    $ret = [];
    foreach ($answers as $answer) {
      $text = $answer['text'];
      $is_correct = (isset($answer['is_correct'])) ? $answer['is_correct'] : null;
      $order = (isset($answer['order'])) ? $answer['order'] : null;
      $ret[] = $this->add($quiz_question_id, $text, $is_correct, $order);
    }

    return $ret;
  }

  public function remove($id)
  {
    return $this->getMapper()->select($this->getModel()->setId($id));
  }
  
  public function _add($quiz_question_id, $text, $is_correct = false, $order = null)
  {
      $m_quiz_answer = $this->getModel()->setQuizQuestionId($quiz_question_id)->setText($text)->setIsCorrect($is_correct);
      if($this->getMapper()->insert($m_quiz_answer) <= 0) {
        throw new \Exception("Error Processing Request", 1);
      }

      return (int) $this->getMapper()->getLastInsertValue();
  }

  public function get($quiz_question_id)
  {
    return $this->getMapper()->select($this->getModel()->setQuizQuestionId($quiz_question_id));
  }
}
