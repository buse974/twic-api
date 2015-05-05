<?php

namespace Application\Model;

use Application\Model\Base\Program as BaseProgram;

class Program extends BaseProgram
{
    const LEVEL_EMBA = 'emaba';
    const LEVELMBA   = 'mba';

    protected $student;
    protected $instructor;
    protected $course;

    public function setStudent($student)
    {
        $this->student = $student;

        return $this;
    }

    public function getStudent()
    {
        return $this->student;
    }

    public function setInstructor($instructor)
    {
        $this->instructor = $instructor;

        return $this;
    }

    public function getInstructor()
    {
        return $this->instructor;
    }

    public function setCourse($course)
    {
        $this->course = $course;

        return $this;
    }

    public function getCourse()
    {
        return $this->course;
    }
}
