<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class Grading extends AbstractMapper
{
    public function getByCourse($course)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'letter', 'min', 'max', 'grade', 'description', 'tpl', 'school_id', 'program_id'))
               ->join('program', 'program.id=grading.program_id')
               ->join('course', 'course.program_id=program.id')
               ->where(array('course.id' => $course));

        return $this->selectWith($select);
    }
}
