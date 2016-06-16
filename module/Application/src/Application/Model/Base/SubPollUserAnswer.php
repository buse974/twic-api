<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class SubPollUserAnswer extends AbstractModel
{
    protected $id;
    protected $sub_poll_user_id;
    protected $bank_question_item_id;
    protected $answer;
    protected $date;
    protected $time;
    protected $created_date;

    protected $prefix = 'sub_poll_user_answer';

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getSubPollUserId()
    {
        return $this->sub_poll_user_id;
    }

    public function setSubPollUserId($sub_poll_user_id)
    {
        $this->sub_poll_user_id = $sub_poll_user_id;

        return $this;
    }

    public function getBankQuestionItemId()
    {
        return $this->bank_question_item_id;
    }

    public function setBankQuestionItemId($bank_question_item_id)
    {
        $this->bank_question_item_id = $bank_question_item_id;

        return $this;
    }

    public function getAnswer()
    {
        return $this->answer;
    }

    public function setAnswer($answer)
    {
        $this->answer = $answer;

        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    public function getTime()
    {
        return $this->time;
    }

    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    public function getCreatedDate()
    {
        return $this->created_date;
    }

    public function setCreatedDate($created_date)
    {
        $this->created_date = $created_date;

        return $this;
    }
}
