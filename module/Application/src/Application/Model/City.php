<?php

namespace Application\Model;

use Application\Model\Base\City as BaseCity;
use Address\Model\Country;
use Address\Model\Division;

class City extends BaseCity
{
    protected $country;
    protected $division;

    public function exchangeArray(array &$data)
    {
        parent::exchangeArray($data);

        $this->country = new Country($this);
        $this->division = new Division($this);

        $this->country->exchangeArray($data);
        $this->division->exchangeArray($data);
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
