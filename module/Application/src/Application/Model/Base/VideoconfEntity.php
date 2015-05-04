<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class VideoconfEntity extends AbstractModel
{
    protected $id;
    protected $videoconf_id;
    protected $name;
    protected $avatar;
    protected $token;

    protected $prefix = 'videoconf_entity';

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getVideoconfId()
    {
        return $this->videoconf_id;
    }

    public function setVideoconfId($videoconf_id)
    {
        $this->videoconf_id = $videoconf_id;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }
}
