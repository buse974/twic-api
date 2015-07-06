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
        	   ->join('videoconf', 'videoconf.conversation_id=conversation_user.conversation_id', array(), $select::JOIN_LEFT)
               ->where(array('user_id' => $users))
               ->where(array('videoconf.id IS NULL'))
               ->where(array('conversation_user.conversation_id' => $select_sub))
               ->group(array('conversation_user.conversation_id'))
               ->having($having);

        return $this->selectWith($select);
    }
}
