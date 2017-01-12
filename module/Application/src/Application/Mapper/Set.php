<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class Set extends AbstractMapper
{
    /**
     * @param int    $set
     * @param string $name
     *
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getList($course, $name = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'uid', 'name', 'course_id'))
            ->where(array('set.course_id' => $course))
            ->where('set.is_used = 0');

        if ($name !== null) {
            $select->where(['set.name LIKE ?' => '%'.$name.'%']);
        }

        return $this->selectWith($select);
    }
}
