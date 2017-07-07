<?php

namespace Application\Model;

use Application\Model\Base\ItemUser as BaseItemUser;

class ItemUser extends BaseItemUser
{
  protected $submission;

  public function exchangeArray(array &$data)
  {
      parent::exchangeArray($data);

      $this->submission = $this->requireModel('app_model_submission', $data);
  }

  /**
   * Get the value of Submission
   *
   * @return mixed
   */
  public function getSubmission()
  {
      return $this->submission;
  }

  /**
   * Set the value of Submission
   *
   * @param mixed submission
   *
   * @return self
   */
  public function setSubmission($submission)
  {
      $this->submission = $submission;

      return $this;
  }

}
