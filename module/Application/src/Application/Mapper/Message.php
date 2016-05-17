<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\Expression;

class Message extends AbstractMapper
{
    public function getNbrMessage($school, $day = null)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('message$nb_message' => new Expression('COUNT(true)')))
            ->join('message_user', 'message_user.message_id=message.id', array())
            ->join('user', 'user.id=message_user.user_id', array())
            ->where(array('message_user.type' => 'S'))
            ->where(array('user.school_id' => $school))
            ->where(array('message.is_draft IS FALSE'))
            ->where(array('message.type=2'));

        if (null !== $day) {
            $select->where(array('message_user.created_date > DATE_ADD(UTC_TIMESTAMP(), INTERVAL -'.$day.' DAY) '));
        }

        return $this->selectWith($select)->current()->getNbMessage();
    }
    
    public function getFullList($conversation_id)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->columns(array(
            'id', 
            'title', 
            'text', 
            'token', 
            'is_draft', 
            'type', 
            'conversation_id', 
            'message$created_date' => new Expression('DATE_FORMAT(message.created_date, "%Y-%m-%dT%TZ")')))
            ->join('message_user', 'message_user.message_id=message.id', ['from_id'])
            ->where(array('message_user.user_id=from_id'))
            ->where(array('message.conversation_id' => $conversation_id));
        
        return $this->selectWith($select);
    }
}
