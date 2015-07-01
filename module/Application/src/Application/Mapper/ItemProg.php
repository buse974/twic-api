<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Expression;

class ItemProg extends AbstractMapper
{
    /**
     * @param User $user
     * @param int $item
     * @param string $start
     * @param string $end
     *
     * @return array
     */
    public function getList($user, $item = null, $start = null, $end = null)
    {
        
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('id', 'item_prog$start_date' => new Expression("DATE_FORMAT(start_date, '%Y-%m-%dT%TZ') ")));
        
        if($item !== null){
            $select->where(array('item_prog.item_id ' => $item));
        }
        if($start !== null && $end !== null){
             $select->join('item','item.id = item_prog.item_id',array('id'))
                    ->join('course','course.id = item.course_id', array('id', 'title'))
                    ->join('module','module.id = item.module_id', array('id', 'title'))
                    ->join('grading_policy','grading_policy.id = item.grading_policy_id', array('name', 'type'))
                     ->where(array('start_date BETWEEN ? AND ? ' => array($start ,$end)));
            if(in_array("instructor",$user['roles'])){
                
                $select->columns(array('id', 'item_prog$editable' => new Expression("1"), 'item_prog$start_date' => new Expression("DATE_FORMAT(start_date, '%Y-%m-%dT%TZ') ")));
                $select->join('course_user_relation','course.id = course_user_relation.course_id')
                        ->where(array('course_user_relation.user_id' => $user['id']));
                
            }
            else{
                 $select->join('item_prog_user','item_prog_user.item_prog_id = item_prog.id',array())   
                        ->where(array('item_prog_user.user_id' => $user['id']));
            }
           
        }
        
        
        return $this->selectWith($select);
    }

    public function getByItemAssignment($item_assignement)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('start_date', 'id'))
               ->join('item_prog', 'item_prog.item_id=item.id', array())
               ->where(array('item_prog.id' => $item_assignement));

        return $this->selectWith($select);
    }
    
    
   
}
