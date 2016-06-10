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
            ->join('submission_user', 'item_assignment_relation.submission_user_id=submission_user.id', array())
            ->join('submission', 'submission.id=submission_user.submission_id', array('id', 'submission$start_date' => new Expression("DATE_FORMAT(start_date, '%Y-%m-%dT%TZ') ")))
            ->join('item_grading', 'item_grading.submission_user_id=submission_user.id', array('grade', 'created_date'), $select::JOIN_LEFT)
            ->join('item', 'item.id=submission.item_id', array('id', 'title', 'describe', 'type'))
            ->join('course', 'course.id=item.course_id', array('id', 'title'))
            ->join('program', 'program.id=course.program_id', array('id', 'name'))
            ->where(array('item_assignment.id' => $id));

        return $this->selectWith($select);
    }

    /**
     * @invokable
     *
     * @param int $submission
     *
     * @throws \Exception
     *
     * @return array
     */
    public function getFromItemProg($user, $submission)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id'))
            ->join('item_assignment_relation', 'item_assignment_relation.item_assignment_id = item_assignment.id', array())
            ->join('submission_user', 'item_assignment_relation.submission_user_id=submission_user.id', array())
            ->where(array('submission_user.user_id' => $user))
            ->where(array('submission_user.submission_id' => $submission));

        return $this->selectWith($select);
    }
}
