<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class IntructorCourse extends AbstractModel
{
    protected $id;
    protected $course_id;
    protected $intructor_id;

    protected $prefix = 'intructor_course';

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

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

    public function getIntructorId()
    {
        return $this->intructor_id;
    }

    public function setIntructorId($intructor_id)
    {
        $this->intructor_id = $intructor_id;

        return $this;
    }
}
