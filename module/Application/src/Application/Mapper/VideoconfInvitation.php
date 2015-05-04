<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class VideoconfInvitation extends AbstractMapper
{
    public function getByVideoconfId($videoconf_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'firstname', 'lastname', 'email', 'avatar', 'utc'))
               ->join('videoconf_entity', 'videoconf_entity.id=videoconf_invitation.videoconf_entity_id', array('id', 'name', 'token', 'avatar'), $select::JOIN_LEFT)
               ->where(array('videoconf_entity.videoconf_id' => $videoconf_id));

        return $this->selectWith($select);
    }
}
