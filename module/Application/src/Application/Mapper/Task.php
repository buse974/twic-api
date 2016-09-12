<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Predicate\Predicate;

class Task extends AbstractMapper
{
    /**
     * Get task.
     *
     * @param int $id
     *
     * @return array
     */
    public function get($id)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('id', 'title', 'content', 'creator_id', 'task$start' => new Expression("DATE_FORMAT(start, '%Y-%m-%dT%TZ') "), 'task$end' => new Expression("DATE_FORMAT(end, '%Y-%m-%dT%TZ') ")))
            ->where(array('task.id ' => $id));

        return $this->selectWith($select);
    }

    /**
     * Get tasks of current user.
     *
     * @param string $start
     * @param string $end
     * @param int    $creator
     *
     * @return array
     */
    public function getList($start, $end, $creator)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('id', 'title', 'content', 'task$start' => new Expression("DATE_FORMAT(start, '%Y-%m-%dT%TZ') "), 'task$end' => new Expression("DATE_FORMAT(end, '%Y-%m-%dT%TZ') "), 'task$editable' => new Expression('task.creator_id = '.$creator)))
            ->join('user', 'user.id = task.creator_id', array('firstname', 'lastname', 'nickname', 'avatar'))
            ->join('task_share', 'task_share.task_id = task.id', array(), $select::JOIN_LEFT)
            ->where(array('( task.creator_id = ? ' => $creator))
            ->where(array(' task_share.user_id = ? )' => $creator), Predicate::OP_OR)
            ->where(array('( task.start BETWEEN ? AND ? ' => array($start, $end)))
            ->where(array('task.end BETWEEN ? AND ?  ' => array($start, $end)), Predicate::OP_OR)
            ->where(array('( task.start < ? AND task.end > ? ) ) ' => array($start, $end)), Predicate::OP_OR)
            ->quantifier('DISTINCT');

        return $this->selectWith($select);
    }
}
