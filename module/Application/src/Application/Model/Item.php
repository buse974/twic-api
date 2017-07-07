<?php

namespace Application\Model;

use Application\Model\Base\Item as BaseItem;

class Item extends BaseItem
{
    const IS_AVAILABLE_ON = 1;
    const IS_AVAILABLE_OFF = 2;
    const IS_AVAILABLE_AUTO = 3;

    const TYPE_SECTION = 'SCT';
    const TYPE_LIVE_CLASS = 'LC';
    const TYPE_ASSIGNMENT = 'A';
    const TYPE_QUIZ = 'QUIZ';
    const TYPE_PAGE = 'PG';
    const TYPE_DISCUSSION = 'DISC';
    const TYPE_MEDIA = 'MEDIA';

    protected $post_id;
    protected $quiz_id;
    protected $nb_total;
    protected $nb_grade;
    protected $nb_submission;
    protected $item_user;
    protected $page_user;

    public function exchangeArray(array &$data)
    {
        parent::exchangeArray($data);

        $this->item_user = $this->requireModel('app_model_item_user', $data);
        $this->page_user = $this->requireModel('app_model_page_user', $data);
    }

    /**
     * Get the value of Post Id
     *
     * @return mixed
     */
    public function getPostId()
    {
        return $this->post_id;
    }

    /**
     * Set the value of Post Id
     *
     * @param mixed post_id
     *
     * @return self
     */
    public function setPostId($post_id)
    {
        $this->post_id = $post_id;

        return $this;
    }


    /**
     * Get the value of Nb Total
     *
     * @return mixed
     */
    public function getNbTotal()
    {
        return $this->nb_total;
    }

    /**
     * Set the value of Nb Total
     *
     * @param mixed nb_total
     *
     * @return self
     */
    public function setNbTotal($nb_total)
    {
        $this->nb_total = $nb_total;

        return $this;
    }

    /**
     * Get the value of Nb Grade
     *
     * @return mixed
     */
    public function getNbGrade()
    {
        return $this->nb_grade;
    }

    /**
     * Set the value of Nb Grade
     *
     * @param mixed nb_grade
     *
     * @return self
     */
    public function setNbGrade($nb_grade)
    {
        $this->nb_grade = $nb_grade;

        return $this;
    }

    /**
     * Get the value of Nb Submission
     *
     * @return mixed
     */
    public function getNbSubmission()
    {
        return $this->nb_submission;
    }

    /**
     * Set the value of Nb Submission
     *
     * @param mixed nb_submission
     *
     * @return self
     */
    public function setNbSubmission($nb_submission)
    {
        $this->nb_submission = $nb_submission;

        return $this;
    }


    /**
     * Get the value of Item User
     *
     * @return mixed
     */
    public function getItemUser()
    {
        return $this->item_user;
    }

    /**
     * Set the value of Item User
     *
     * @param mixed item_user
     *
     * @return self
     */
    public function setItemUser($item_user)
    {
        $this->item_user = $item_user;

        return $this;
    }


    /**
     * Get the value of Page User
     *
     * @return mixed
     */
    public function getPageUser()
    {
        return $this->page_user;
    }

    /**
     * Set the value of Page User
     *
     * @param mixed page_user
     *
     * @return self
     */
    public function setPageUser($page_user)
    {
        $this->page_user = $page_user;

        return $this;
    }


    /**
     * Get the value of Quiz Id
     *
     * @return mixed
     */
    public function getQuizId()
    {
        return $this->quiz_id;
    }

    /**
     * Set the value of Quiz Id
     *
     * @param mixed quiz_id
     *
     * @return self
     */
    public function setQuizId($quiz_id)
    {
        $this->quiz_id = $quiz_id;

        return $this;
    }

}
