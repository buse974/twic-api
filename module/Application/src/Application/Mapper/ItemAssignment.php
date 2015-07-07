<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Predicate\Predicate;

class ItemAssignment extends AbstractMapper
{
    public function get($id, $user)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('id', 'response', 'item_assignment$submit_date' => new Expression("DATE_FORMAT(submit_date, '%Y-%m-%dT%TZ') ")))
        ->join('item_assignment_user', 'item_assignment_user.item_assignment_id = item_assignment.id', array())
        ->join('item_prog', 'item_prog.id=item_assignment.item_prog_id', array('item_prog$start_date' => new Expression("DATE_FORMAT(start_date, '%Y-%m-%dT%TZ') ")))
        ->join('item_prog_user', 'item_prog_user.item_prog_id=item_prog.id AND item_prog_user.user_id = item_assignment_user.user_id', array())
        ->join('item_grading', 'item_grading.item_prog_user_id=item_prog_user.id', array('grade', 'created_date'), $select::JOIN_LEFT)
        ->join('item', 'item.id=item_prog.item_id', array('id', 'title', 'describe', 'type'))
        ->join('module', 'module.id=item.module_id', array('id', 'title'))
        ->join('course', 'course.id=module.course_id', array('id', 'title'))
        ->join('course_user_relation', 'course.id=course_user_relation.course_id', array())
        ->join('user', 'user.id=course_user_relation.user_id', array())
        ->join('user_role', 'user_role.user_id=user.id', array())
        ->join('program', 'program.id=course.program_id', array('id', 'name'))
        ->where(array('item_assignment.id' => $id))
        ->where(array('( item_assignment_user.user_id = ? ' => $user))
         ->where(array('( user_role.role_id = ? ' => \Application\Model\Role::ROLE_INSTRUCTOR_ID), Predicate::OP_OR)
         ->where(array(' course_user_relation.user_id = ? ))' => $user));
        
        //exit($this->printSql($select));
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
                ->join('item_prog', 'item_prog.id=item_assignment.item_prog_id', array())
                ->join('item_prog_user', 'item_prog_user.item_prog_id=item_prog.id', array())
                ->join('item_assignment_user', 'item_assignment.id=item_assignment_user.item_assignment_id', array())
                ->where(array('item_assignment_user.user_id' => $user))
                ->where(array('item_prog.id' => $item_prog));
                //->where(array('item_prog.due_date <= CURRENT_TIMESTAMP()'));
        
        return $this->selectWith($select);
     
     
        
    }
}
