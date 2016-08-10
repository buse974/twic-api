<?php

namespace Application\Model;

use Application\Model\Base\Activity as BaseActivity;

class Activity extends BaseActivity
{
    protected $user;
    protected $value_user;
    protected $value_total;
    protected $school_id;
    protected $program_id;
    protected $course_id;
    protected $item_id;

    public function exchangeArray(array &$data)
    {
        parent::exchangeArray($data);

        $this->user = $this->requireModel('app_model_user', $data);
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    public function getValueTotal()
    {
        return $this->value_total;
    }

    public function setValueTotal($value_total)
    {
        $this->value_total = $value_total;

        return $this;
    }

    public function getValueUser()
    {
        return $this->value_user;
    }

    public function setValueUser($value_user)
    {
        $this->value_user = $value_user;

        return $this;
    }

    public function getSchoolId()
    {
        return $this->school_id;
    }

    public function setSchoolId($school_id)
    {
        $this->school_id = $school_id;

        return $this;
    }

    public function getProgramId()
    {
        return $this->program_id;
    }

    public function setProgramId($program_id)
    {
        $this->program_id = $program_id;

        return $this;
    }

    public function getCourseId()
    {
        return $this->course_id;
    }

    public function setCourseId($course_id)
    {
        $this->course_id = $course_id;

        return $this;
    }

    public function getItemId()
    {
        return $this->item_id;
    }

    public function setItemId($item_id)
    {
        $this->item_id = $item_id;

        return $this;
    }
}
