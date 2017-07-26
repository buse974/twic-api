<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class QuizUser extends AbstractService
{
  public function add($quiz_question_id, $quiz_answer_id = null, $text = null)
  {
    $quiz_id = $this->getServiceQuizQuestion()->getLite($quiz_question_id)->current()->getQuizId();
    $identity = $this->getServiceUser()->getIdentity();
    $m_quiz_user = $this->getModel()
      ->setQuizId($quiz_id)
      ->setQuizQuestionId($quiz_question_id)
      ->setUserId($identity['id'])
      ->setQuizAnswerId($quiz_answer_id)
      ->setText($text);

    if(!$this->getMapper()->insert($m_quiz_user)) {
      throw new \Exception("Error Processing Request", 1);
    }

    return (int) $this->getMapper()->getLastInsertValue();
  }

  public function update($id, $quiz_answer_id = null, $text = null)
  {
    $identity = $this->getServiceUser()->getIdentity();
    $m_quiz_user = $this->getModel()
      ->setQuizAnswerId($quiz_answer_id)
      ->setText($text);

    return $this->getMapper()->update($m_quiz_user, ['id' => $id, 'user_id' => $identity['id']]);
  }

  public function remove($id)
  {
    $identity = $this->getServiceUser()->getIdentity();
    $m_quiz_user = $this->getModel()->setId($id)->setUserId($identity['id']);

    return $this->getMapper()->delete($m_quiz_user);
  }

  public function get($quiz_id, $user_id = null)
  {
    $ar = [];
    if(!is_array($quiz_id)) {
      $quiz_id = [$quiz_id];
    }
    foreach ($quiz_id as $q_id) {
      $ar[$q_id] = [];
    }

    $identity = $this->getServiceUser()->getIdentity();
    $is_admin = false;
    if(null !== $user_id) {
      $m_quiz = $this->getServiceQuiz()->getLite($quiz_id);
      if($m_quiz && is_numeric($m_quiz->getItemId())) {
        $m_item = $this->getServiceItem()->getLite($m_quiz->getItemId())->current();
        $page_id = $m_item->getPageId();
        $ar_pu = $this->getServicePageUser()->getListByPage($page_id, 'admin');
        $is_admin = in_array($identity['id'], $ar_pu[$page_id]);
      }
    }

    if(null === $user_id || (null !== $user_id && $is_admin === false) ) {
      $user_id = $identity['id'];
    }

    $res_quiz_user = $this->getMapper()->select($this->getModel()->setQuizId($quiz_id)->setUserId($user_id));
    foreach ($res_quiz_user as $m_quiz_user) {
      $ar[$m_quiz_user->getQuizId()][] = $m_quiz_user->toArray();
    }

    return $ar;
  }

  /**
   * Get Service Quiz User
   *
   * @return \Application\Service\getServiceQuizQuestion
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

  /**
   * Get Service Item
   *
   * @return \Application\Service\Item
   */
  public function getServiceItem()
  {
      return $this->container->get('app_service_item');
  }

  /**
   * Get Service Quiz
   *
   * @return \Application\Service\Quiz
   */
  public function getServiceQuiz()
  {
      return $this->container->get('app_service_quiz');
  }

  /**
   * Get Service Page User
   *
   * @return \Application\Service\PageUser
   */
  public function getServicePageUser()
  {
      return $this->container->get('app_service_page_user');
  }

}
