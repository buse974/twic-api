<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class VideoconfInvitation extends AbstractModel
{
    protected $id;
    protected $videoconf_entity_id;
    protected $firstname;
    protected $lastname;
    protected $avatar;
    protected $email;
    protected $utc;

    protected $prefix = 'videoconf_invitation';

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getVideoconfEntityId()
    {
        return $this->videoconf_entity_id;
    }

    public function setVideoconfEntityId($videoconf_entity_id)
    {
        $this->videoconf_entity_id = $videoconf_entity_id;

        return $this;
    }

    public function getFirstname()
    {
        return $this->firstname;
    }

    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname()
    {
        return $this->lastname;
    }

    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

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

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getUtc()
    {
        return $this->utc;
    }

    public function setUtc($utc)
    {
        $this->utc = $utc;

        return $this;
    }
}
