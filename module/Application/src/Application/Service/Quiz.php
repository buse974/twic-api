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
    $identity = $this->getServiceUser()->getIdentity();
    $m_quiz = $this->getModel()
      ->setName($name)
      ->setItemId($item_id)
      ->setUserId($identity['id'])
      ->setAttemptCount($attempt_count)
      ->setTimeLimit($time_limit);

    if(!$this->getMapper()->insert($m_quiz)) {
      throw new \Exception("Error Processing Request", 1);
    }

    $quiz_id = (int) $this->getMapper()->getLastInsertValue();

    if(null !== $questions) {
      $this->getServiceQuizQuestion()->_add($quiz_id, $questions);
    }

    return $quiz_id;
  }

  /**
   * Get Quiz
   *
   * @invokable
   *
   * @param  string $id
   *
   * @return int
   */
  public function get($id)
  {
    $m_quiz = $this->getMapper()->select($this->getModel()->setId($id))->current();

    $m_quiz->setQuizQuestion($this->getServiceQuizQuestion()->get($id));

    return $m_quiz;
  }

  public function update($id, $item_id = null, $name = null, $attempt_count = null, $time_limit = null)
  {
    $m_quiz = $this->getModel()->setId($id)->setItemId($item_id)->setName($name)->setAttemptCount($attempt_count)->setTimeLimit($time_limit);

    return $this->getMapper()->update($m_quiz);
  }

  /**
   * Get Service SQuiz Question
   *
   * @return \Application\Service\QuizQuestion
   */
  public function getServiceQuizQuestion()
  {
      return $this->container->get('app_service_quiz_question');
  }

  /**
   * Get Service User
   *
   * @return \Application\Service\User
   */
  public function getServiceUser()
  {
      return $this->container->get('app_service_user');
  }
}
