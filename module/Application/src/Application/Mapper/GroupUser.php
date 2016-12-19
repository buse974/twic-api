<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class GroupUser extends AbstractMapper
{
    /**
     * @param int $item_id
     * @param int $user
     *
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getGroupIdByItemUser($item_id, $user_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['group_id'])
            ->join('set_group', 'set_group.group_id=group_user.group_id', [])
            ->join('item', 'item.set_id=set_group.set_id', [])
            ->where(array('group_user.user_id' => $user_id))
            ->where(array('item.id' => $item_id));

        return $this->selectWith($select);
    }
}
