<?php
namespace LinkedIn\Model;

class People extends AbstractModel
{
    protected $first_name;
    protected $headline;
    protected $id;
    protected $last_name;
    protected $site_standard_profile_request;

    public function getFirstName()
    {
        return $this->first_name;
    }

    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;
        
        return $this;
    }

    public function getHeadline()
    {
        return $this->headline;
    }

    public function setHeadline($headline)
    {
        $this->headline = $headline;
        
        return $this;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setId($id)
    {
        $this->id = $id;
        
        return $this;
    }
    
    public function getLastName()
    {
        return $this->last_name;
    }
    
    public function setLastName($last_name)
    {
        $this->last_name = $last_name;
        
        return $this;
    }
    
    public function getSiteStandardProfileRequest()
    {
        return $this->site_standard_profile_request;
    }
    
    public function setSiteStandardProfileRequest($site_standard_profile_request)
    {
        $this->site_standard_profile_request = $site_standard_profile_request;
        
        return $this;
    }
}
