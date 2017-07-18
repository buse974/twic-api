<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class Quiz extends AbstractService
{

  /**
   * Create Quiz
   *
   * @invokable
   *
   * @param  string $name
   * @param  string $item_id
   * @param  string $attempt_count
   * @param  string $time_limit
   * @param  array  $questions
   *
   * @return int
   */
  public function add($name, $item_id = null, $attempt_count = null, $time_limit = null, $questions = null)
  {
    $m_quiz = $this->getModel()->setName($name)->setItemId($item_id)->setAttemptCount($attempt_count)->setTimeLimit($time_limit);

    if(!$this->getMapper()->insert($m_quiz)) {
      throw new \Exception("Error Processing Request", 1);
    }

    $quiz_id = (int) $this->getMapper()->getLastInsertValue();


    return $quiz_id;
  }

  public function update($id, $item_id = null, $name = null, $attempt_count = null, $time_limit = null)
  {
    $m_quiz = $this->getModel()->setId($id)->setItemId($item_id)->setName($name)->setAttemptCount($attempt_count)->setTimeLimit($time_limit);

    return $this->getMapper()->update($m_quiz);
  }
}
