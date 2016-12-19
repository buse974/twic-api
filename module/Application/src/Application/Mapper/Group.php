<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class Group extends AbstractMapper
{
    /**
     * @param int    $set
     * @param string $name
     *
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getList($set = null, $name = null, $course)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'uid', 'name'))
            ->join('set_group', 'set_group.group_id=group.id')
            ->join('set', 'set_group.set_id=set.id')
            ->where(['set.course_id' => $course]);

        if ($set !== null) {
            $select->where(['set_group.set_id' => $set]);
        }
        if ($name !== null) {
            $select->where(['group.name LIKE ?' => '%'.$name.'%']);
        }

        return $this->selectWith($select);
    }
}
