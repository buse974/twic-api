<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Expression;

class ItemAssignmentComment extends AbstractMapper
{
    public function getList($item, $user)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('id', 'text', 'audio','file', 'file_name', 'item_assignment_id', 'item_assignment_comment$created_date' => new Expression("DATE_FORMAT(created_date, '%Y-%m-%dT%TZ') "), 'item_assignment_comment$read_date' => new Expression("DATE_FORMAT(read_date, '%Y-%m-%dT%TZ') ")))
            ->join('user', 'user.id=item_assignment_comment.user_id', array('id', 'firstname', 'lastname', 'avatar'))
            ->join('item_assignment', 'item_assignment.id=item_assignment_comment.item_assignment_id', array())
            ->join('item_assignment_relation', 'item_assignment_relation.item_assignment_id = item_assignment.id', array())
            ->join('submission_user', 'item_assignment_relation.submission_user_id=submission_user.id', array())
            ->join('submission', 'submission.id=submission_user.submission_id', array())
            ->where(array('submission.item_id' => $item))
            ->where(array('submission_user.user_id' => $user));

        return $this->selectWith($select);
    }

    public function getListByItemAssignment($item_assignment)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('id', 'text', 'audio', 'file', 'file_name', 'item_assignment_id', 'item_assignment_comment$created_date' => new Expression("DATE_FORMAT(created_date, '%Y-%m-%dT%TZ') "), 'item_assignment_comment$read_date' => new Expression("DATE_FORMAT(read_date, '%Y-%m-%dT%TZ') ")))
            ->join('user', 'item_assignment_comment.user_id=user.id', array('id', 'firstname', 'lastname', 'avatar'))
            ->where(array('item_assignment_comment.item_assignment_id' => $item_assignment));

        return $this->selectWith($select);
    }
}
