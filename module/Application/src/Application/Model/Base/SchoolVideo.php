<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class SchoolVideo extends AbstractModel
{
    protected $id;
    protected $token;
    protected $url;
    protected $name;
    protected $school_id;

    protected $prefix = 'school_video';

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

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

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;

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

    public function getSchoolId()
    {
        return $this->school_id;
    }

    public function setSchoolId($school_id)
    {
        $this->school_id = $school_id;

        return $this;
    }
}
