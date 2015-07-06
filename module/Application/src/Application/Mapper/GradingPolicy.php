<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\Predicate;
use Zend\Db\Sql\Predicate\Expression;

class GradingPolicy extends AbstractMapper
{
    public function getListByCourse($course, $user)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('id', 'name', 'grade', 'grading_policy$nbr_comment' => new Expression('CAST(SUM(IF(grading_policy_grade_comment.id IS NOT NULL, 1, 0)) AS INTEGER )')))
                ->join('grading_policy_grade', 'grading_policy_grade.grading_policy_id=grading_policy.id', array('grade'), $select::JOIN_LEFT)
                ->join('grading_policy_grade_comment', 'grading_policy_grade_comment.grading_policy_grade_id=grading_policy_grade.id', array(), $select::JOIN_LEFT)
                ->where(array(' ( grading_policy_grade.user_id = ? ' => $user))
                ->where(array(' grading_policy_grade.user_id IS NULL ) '), Predicate::OP_OR)
                ->where(array('grading_policy.course_id' => $course))
                ->group('grading_policy.id');

        return $this->selectWith($select);
    }    
    
     /**   
     *   
     * @param int  $user
     * 
     * @return array 
     */
    public function processGrade($user){
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id' ,'grading_policy$processed_grade' => new Expression('CAST(SUM(item_grading.grade * item.weight) / SUM(item.weight) AS INTEGER )' )))
               ->join('item', 'grading_policy.id = item.grading_policy_id',array())
               ->join('item_prog', 'item.id = item_prog.item_id',array())
               ->join('item_prog_user', 'item_prog.id = item_prog_user.item_prog_id ',array())
               ->join('item_grading', 'item_prog_user.id = item_grading.item_prog_user_id ',array())
               ->where(array('item_prog_user.user_id' => $user))
               ->group('grading_policy.id');
        return $this->selectWith($select);
        
    }
    

}
