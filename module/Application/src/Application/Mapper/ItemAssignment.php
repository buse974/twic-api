<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Expression;

class ItemAssignment extends AbstractMapper
{
    public function get($id)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('id', 'response', 'item_assignment$submit_date' => new Expression("DATE_FORMAT(submit_date, '%Y-%m-%dT%TZ') ")))
            ->join('item_assignment_relation', 'item_assignment_relation.item_assignment_id = item_assignment.id', array())
            ->join('item_prog_user', 'item_assignment_relation.item_prog_user_id=item_prog_user.id', array())
            ->join('item_prog', 'item_prog.id=item_prog_user.item_prog_id', array('id', 'item_prog$due_date' => new Expression("DATE_FORMAT(due_date, '%Y-%m-%dT%TZ') "), 'item_prog$start_date' => new Expression("DATE_FORMAT(start_date, '%Y-%m-%dT%TZ') ")))
            ->join('item_grading', 'item_grading.item_prog_user_id=item_prog_user.id', array('grade', 'created_date'), $select::JOIN_LEFT)
            ->join('item', 'item.id=item_prog.item_id', array('id', 'title', 'describe', 'type'))
            ->join('module', 'module.id=item.module_id', array('id', 'title'), $select::JOIN_LEFT)
            ->join('course', 'course.id=item.course_id', array('id', 'title'))
            ->join('program', 'program.id=course.program_id', array('id', 'name'))
            ->where(array('item_assignment.id' => $id));

        return $this->selectWith($select);
    }

    /**
     * @invokable
     *
     * @param int $item_prog
     *
     * @throws \Exception
     *
     * @return array
     */
    public function getFromItemProg($user, $item_prog)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id'))
            ->join('item_assignment_relation', 'item_assignment_relation.item_assignment_id = item_assignment.id', array())
            ->join('item_prog_user', 'item_assignment_relation.item_prog_user_id=item_prog_user.id', array())
            ->where(array('item_prog_user.user_id' => $user))
            ->where(array('item_prog_user.item_prog_id' => $item_prog));

        return $this->selectWith($select);
    }
}
