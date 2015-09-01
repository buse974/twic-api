<?php

namespace Application\Model;

use Application\Model\Base\Answer as BaseAnswer;

class Answer extends BaseAnswer
{
    protected $scale;
    protected $component;
    protected $dimension;
    protected $gender;
    protected $nationality;
    protected $nationality_name;
    protected $origin;
    protected $origin_name;
    
    public function getOriginName() 
    {
        return $this->origin_name;
    }

    public function setOriginName($origin_name) 
    {
        $this->origin_name = $origin_name;
        
        return $this;
    }
    
    public function getOrigin() 
    {
        return $this->origin;
    }
     
    public function setOrigin($origin) 
    {
        $this->origin = $origin;
        
        return $this;
    }

    public function getNationalityName() 
    {
        return $this->nationality_name;
    }
     
    public function setNationalityName($nationality_name) 
    {
        $this->nationality_name = $nationality_name;
        
        return $this;
    }

    public function getNationality() 
    {
        return $this->nationality;
    }
     
    public function setNationality($nationality) 
    {
        $this->nationality = $nationality;
        
        return $this;
    }
    
    public function getGender() 
    {
        return $this->gender;
    }

    public function setGender($gender) 
    {
        $this->gender = $gender;
        
        return $this;
    }
    
    public function getDimension() 
    {
        return $this->dimension;
    }
     
    public function setDimension($dimension) 
    {
        $this->dimension = $dimension;
        
        return $this;
    }

    public function getComponent() 
    {
        return $this->component;
    }
     
    public function setComponent($component) 
    {
        $this->component = $component;
        
        return $this;
    }
    
    public function getScale() 
    {
        return $this->scale;
    }
     
    public function setScale($scale) 
    {
        $this->scale = $scale;
        
        return $this;
    }
}