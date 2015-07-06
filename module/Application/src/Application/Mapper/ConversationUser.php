<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class ConversationUser extends AbstractMapper
{
    /**
     * @param array $users
     *
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getConversationByUser($users)
    {
        $having = new \Zend\Db\Sql\Having();
        $having->expression('COUNT(1) = ?', count($users));

        $select_sub = $this->tableGateway->getSql()->select();
        $select_sub->columns(array('conversation_id'))
        ->group(array('conversation_id'))
        ->having($having);

        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('conversation_id'))
        ->where(array('user_id' => $users))
        ->where(array('conversation_id' => $select_sub))
        ->group(array('conversation_id'))
        ->having($having);

        return $this->selectWith($select);
    }
}
