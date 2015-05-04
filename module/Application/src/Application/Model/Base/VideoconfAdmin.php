<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class VideoconfAdmin extends AbstractModel
{
    protected $id;
    protected $videoconf_id;
    protected $user_id;
    protected $token;
    protected $created_date;

    protected $prefix = 'videoconf_admin';

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

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

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

    public function getCreatedDate()
    {
        return $this->created_date;
    }

    public function setCreatedDate($created_date)
    {
        $this->created_date = $created_date;

        return $this;
    }
}
