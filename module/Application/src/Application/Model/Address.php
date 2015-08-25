<?php

namespace Application\Model;

use Application\Model\Base\Address as BaseAddress;
use Address\Model\Country;
use Address\Model\City;
use Address\Model\Division;

class Address extends BaseAddress
{
    protected $country;
    protected $city;
    protected $division;

    public function exchangeArray(array &$data)
    {
        parent::exchangeArray($data);

        $this->country = new Country($this);
        $this->division = new Division($this);
        $this->city = new City($this);

        $this->country->exchangeArray($data);
        $this->division->exchangeArray($data);
        $this->city->exchangeArray($data);
    }

    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    public function getContry()
    {
        return $this->country;
    }

    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    public function getCity()
    {
        return $this->city;
    }
    
    public function setDivision($division)
    {
        $this->division = $division;

        return $this;
    }

    public function getDivision()
    {
        return $this->division;
    }
}
