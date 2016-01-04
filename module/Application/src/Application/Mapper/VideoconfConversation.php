<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class VideoconfConversation extends AbstractMapper
{
    public function getByVideoconfUser($videoconf, $user)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('conversation_id'))
            ->join('conversation_user', 'videoconf_conversation.conversation_id=conversation_user.conversation_id')
            ->where(array('videoconf_conversation.videoconf_id' => $videoconf))
            ->where(array('conversation_user.user_id' => $user));

        return $this->selectWith($select);
    }
}
