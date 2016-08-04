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
            ->where(['conversation_whiteboard.conversation_id' => $conversation_id]);

        return $this->selectWith($select);
    }

    public function getList($submission_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id', 'name', 'owner_id', 'width', 'height'])
            ->join('sub_whiteboard', 'sub_whiteboard.whiteboard_id=whiteboard.id', [])
            ->where(['sub_whiteboard.submission_id' => $submission_id]);

        return $this->selectWith($select);
    }
}
