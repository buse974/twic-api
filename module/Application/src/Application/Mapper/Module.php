<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class Module extends AbstractMapper
{
    /**
     * Get Last parent id.
     *
     * @param number $question_item
     *
     * @return number
     */
    public function selectLastParentId($course)
    {
        $req = 'SELECT id FROM module WHERE id NOT IN (SELECT parent_id FROM module WHERE parent_id IS NOT null) AND course_id = :course';
        $res = $this->selectPdo($req, array(':course' => $course));

        return (($res->count() > 0) ? $res->current()->getId() : null);
    }
}
