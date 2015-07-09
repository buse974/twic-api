<?php
namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Expression;

class ItemAssignmentComment extends AbstractMapper
{

    public function getList($item, $user)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->columns(array('id','text','item_assignment_id','item_assignment_comment$created_date' => new Expression("DATE_FORMAT(created_date, '%Y-%m-%dT%TZ') "),'item_assignment_comment$read_date' => new Expression("DATE_FORMAT(read_date, '%Y-%m-%dT%TZ') ")))
            ->join('user', 'user.id=item_assignment_comment.user_id', array('id','firstname','lastname','avatar'))
            ->join('item_assignment', 'item_assignment.id=item_assignment_comment.item_assignment_id', array())
            ->join('item_assignment_relation', 'item_assignment_relation.item_assignment_id = item_assignment.id', array())
            ->join('item_prog_user', 'item_assignment_relation.item_prog_user_id=item_prog_user.id', array())
            ->join('item_prog', 'item_prog.id=item_prog_user.item_prog_id', array())
            ->where(array('item_prog.item_id' => $item))
            ->where(array('item_prog_user.user_id' => $user));
        
        return $this->selectWith($select);
    }

    public function getListByItemAssignment($item_assignment)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->columns(array('id','text','item_assignment_id','item_assignment_comment$created_date' => new Expression("DATE_FORMAT(created_date, '%Y-%m-%dT%TZ') "),'item_assignment_comment$read_date' => new Expression("DATE_FORMAT(read_date, '%Y-%m-%dT%TZ') ")))
            ->join('user', 'item_assignment_comment.user_id=user.id', array('id','firstname','lastname','avatar'))
            ->where(array('item_assignment_comment.item_assignment_id' => $item_assignment));
        
        return $this->selectWith($select);
    }
}
