<?php

namespace Application\Model;

use Application\Model\Base\Videoconf as BaseVideoconf;

class Videoconf extends BaseVideoconf
{
    const FINISHED = 'finished';
    const ONGOING = 'ongoing';
    const NOTSTARTED = 'notstarted';

    const ARV_AVAILABLE = 'available';
    const ARV_EXPIRED   = 'expired';
    const ARV_FAILED    = 'failed';
    const ARV_STARTED   = 'started';
    const ARV_STOPPED   = 'stopped';
    const ARV_UPLOAD    = 'uploaded';

    protected $videoconf_entity;
    protected $videoconf_invitation;
    protected $videoconf_admin;

    public function exchangeArray(array &$data)
    {
        parent::exchangeArray($data);

        $this->videoconf_invitation = new VideoconfInvitation($this);
        $this->videoconf_invitation->exchangeArray($data);

        $this->videoconf_entity = new VideoconfEntity($this);
        $this->videoconf_entity->exchangeArray($data);

        $this->videoconf_admin = new VideoconfAdmin($this);
        $this->videoconf_admin->exchangeArray($data);
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

    public function setVideoconfInvitation($videoconf_invitation)
    {
        $this->videoconf_invitation = $videoconf_invitation;

        return $this;
    }

    public function getVideoconfInvitation()
    {
        return $this->videoconf_invitation;
    }

    public function setVideoconfAdmin($videoconf_admin)
    {
        $this->videoconf_admin = $videoconf_admin;

        return $this;
    }

    public function getVideoconfAdmin()
    {
        return $this->videoconf_admin;
    }
}
