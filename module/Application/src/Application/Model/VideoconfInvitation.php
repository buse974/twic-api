<?php

namespace Application\Model;

use Application\Model\Base\VideoconfInvitation as BaseVideoconfInvitation;

class VideoconfInvitation extends BaseVideoconfInvitation
{
    protected $videoconf_entity;

    public function exchangeArray(array &$data)
    {
        parent::exchangeArray($data);

        $this->videoconf_entity = new VideoconfEntity($this);
        $this->videoconf_entity->exchangeArray($data);
    }

    public function setVideoconfEntity($videoconf_entity)
    {
        $this->videoconf_entity = $videoconf_entity;

        return $this;
    }

    public function getVideoconfEntity()
    {
        return $this->videoconf_entity;
    }
}
