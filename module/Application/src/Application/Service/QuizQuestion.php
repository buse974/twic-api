<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class QuizQuestion extends AbstractService
{
  /**
   * Add Quiz Question
   *
   * @param  string $quiz_id
   * @param  array $questions
   *
   * @return int
   */
  public function add($quiz_id, $questions)
  {
    $ret = [];
    foreach ($questions as $question) {
      $text    = $question['text'];
      $type    = $question['type'];
      $point   = (isset($question['point'])) ? $question['point'] : null;
      $order   = (isset($question['order'])) ? $question['order'] : null;
      $answers = (isset($question['answers'])) ? $question['answers'] : null;
      $ret[] = $this->_add($quiz_id, $text, $type, $point, $order, $answers);
    }

    return $ret;
  }

  public function remove($id)
  {
    return $this->getMapper()->delete($this->getModel()->setId($id));
  }

  /**
   * Update Quiz Question

   * @param array $questions
   */
  public function update($questions)
  {
    $ret = [];
    foreach ($questions as $question) {
      $id      = $question['id'];
      $text    = (isset($question['text'])) ? $question['text'] : null;
      $type    = (isset($question['type'])) ? $question['type'] : null;
      $point   = (isset($question['point'])) ? $question['point'] : null;

      $ret[] = $this->getMapper()->update($this->getModel()->setText($text)->setType($type)->setPoint($point)->setId($id));
    }

    return $ret;
  }

  public function get($quiz_id)
  {
    $res_quiz_question = $this->getMapper()->select($this->getModel()->setQuizId($quiz_id));

    foreach ($res_quiz_question as $m_quiz_question) {
      $m_quiz_question->setQuizAnswer($this->getServiceQuizAnswer()->get($m_quiz_question->getId()));
    }

    return $res_quiz_question;
  }

  public function _add($quiz_id, $text, $type, $point = null, $order = null, $answers = null)
  {
      $m_quiz_question = $this->getModel()->setText($text)->setType($type)->setPoint($point)->setQuizId($quiz_id);
      if($this->getMapper()->insert($m_quiz_question) <= 0) {
        throw new \Exception("Error Processing Request", 1);
      }

      $quiz_question_id = (int) $this->getMapper()->getLastInsertValue();

      if(null !== $answers) {
        $this->getServiceQuizAnswer()->add($quiz_question_id, $answers);
      }

      return $quiz_question_id;
  }

  /**
   * Get Service Quiz Answer
   *
   * @return \Application\Service\QuizAnswer
   */
  public function getServiceQuizAnswer()
  {
      return $this->container->get('app_service_quiz_answer');
  }
}
