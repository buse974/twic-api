<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class CourseUserRelation extends AbstractModel
{
    protected $user_id;
    protected $course_id;

    protected $prefix = 'course_user_relation';

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

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
}
