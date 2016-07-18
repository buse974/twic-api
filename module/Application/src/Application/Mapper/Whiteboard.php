<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class Whiteboard extends AbstractMapper
{
    public function getListByConversation($conversation_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id', 'name', 'owner_id', 'width', 'height'])
        ->join('conversation_whiteboard', 'conversation_whiteboard.whiteboard_id=whiteboard.id', [])
        ->where(array('conversation_whiteboard.conversation_id' =>  $conversation_id));
    
        return $this->selectWith($select);
    }
}