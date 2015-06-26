<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\NotIn;
use Zend\Db\Sql\Predicate\Predicate;
use Zend\Db\Sql\Predicate\Expression;
use Dal\Db\Sql\Select;

class Item extends AbstractMapper
{
    public function getListGrade($programs, $courses, $type, $notgraded, $newMessage, $filter)
    {
        $select = $this->tableGateway->getSql()->select();

        $select_new_message = new Select('item_assignment_comment');
        $select_new_message->columns(array('rrr' => new Expression('COUNT(1)')))
                           ->where(array('item_assignment_comment.item_assignment_id=item_assignment.id'))
                           ->where(array('item_assignment_comment.read_date IS NULL'));

        $select->columns(array('title', 'item$new_message' => $select_new_message))
               ->join('module', 'module.id=item.module_id', array('id', 'title'), $select::JOIN_LEFT)
               ->join('course', 'course.id=item.course_id', array('id', 'title'))
               ->join('program', 'program.id=course.program_id', array('id', 'name'))
               ->join('item_prog', 'item_prog.item_id=item.id', array('start_date'))
               ->join('item_assignment', 'item_assignment.item_prog_id=item_prog.id', array('id', 'submit_date'))
               ->join('item_prog_user', 'item_prog_user.item_prog_id=item_prog.id', array(), $select::JOIN_LEFT)
               ->join('item_grading', 'item_grading.item_prog_user_id=item_prog_user.id', array('grade', 'created_date'), $select::JOIN_LEFT)
               ->join('grading', 'item_grading.grade BETWEEN grading.min AND grading.max', array('item_grading$letter' => 'letter'), $select::JOIN_LEFT)
               ->join('user', 'item_prog_user.user_id=user.id', array())
               ->where(array('program.id' => $programs))
               ->where(array('item_assignment.submit_date IS NOT NULL'))
               ->quantifier('DISTINCT');

        if ($courses !== null) {
            $select->where(array('course.id' => $courses));
        }
        if ($type !== null) {
            $select->where(array('item.type' => $type));
        }
        if (isset($filter['search'])) {
            $select->where(array(' ( user.firstname LIKE ?' => $filter['search'].'%'))
                   ->where(array('user.lastname LIKE ? ) ' => $filter['search'].'%'), Predicate::OP_OR);
        }
        if ($notgraded === true) {
            $select->where(array('item_grading.id IS NULL'));
        }
        if ($newMessage === true) {
            $select->join('item_assignment_comment', 'item_assignment_comment.item_assignment_id=item_assignment.id', array(), $select::JOIN_LEFT)
                   ->where(array('item_assignment_comment.read_date IS NULL'));
        }

        return $this->selectWith($select);
    }

    public function getByItemProg($item_prog)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('type'))
               ->join('item_prog', 'item_prog.item_id=item.id', array())
               ->where(array('item_prog.id' => $item_prog));

        return $this->selectWith($select);
    }

    /**
     * Get Last parent id.
     *
     * @param int $course
     *
     * @return int
     */
    public function selectLastParentId($course = null, $id = null)
    {
        if ($course === null && $id = null) {
            throw new \Exception('Course and id are null');
        }
        if ($course === null) {
            $course = $this->tableGateway->getSql()->select();
            $course->columns(array('course_id'))
                   ->where(array('id' => $id));
        }

        $select = $this->tableGateway->getSql()->select();
        $subselect = $this->tableGateway->getSql()->select();

        $subselect->columns(array('parent_id'))
                  ->where(array('parent_id IS NOT NULL'))
                  ->where(array('course_id' => $course));

        $select->columns(array('id'))
               ->where(array(new NotIn('id', $subselect)))
               ->where(array('course_id' => $course));

        $res = $this->selectWith($select);

        return (($res->count() > 0) ? $res->current()->getId() : null);
    }

    /**
     * @param int $course
     * @param int $user
     */
    public function getListGradeItem($grading_policy, $course, $user)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('id','title', 'grading_policy_id', 'item$nbr_comment' => new Expression('CAST(SUM(IF(item_assignment_comment.id IS NOT NULL, 1, 0)) AS INTEGER )')))
               ->join('item_prog', 'item_prog.item_id=item.id', array())
               ->join('item_prog_user', 'item_prog_user.item_prog_id=item_prog.id', array())
               ->join('item_grading', 'item_grading.item_prog_user_id=item_prog_user.id', array('grade', 'created_date'))
               ->join('item_assignment', 'item_assignment.item_prog_id=item_prog.id', array(), $select::JOIN_LEFT)
               ->join('item_assignment_comment', 'item_assignment_comment.item_assignment_id=item_assignment.id', array(), $select::JOIN_LEFT)
               ->where(array('item.course_id' => $course))
               ->where(array('item.grading_policy_id' => $grading_policy))
               ->where(array('item_prog_user.user_id' => $user))
               ->group('item.id');

        return $this->selectWith($select);
    }
}
