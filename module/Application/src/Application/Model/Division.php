<?php

namespace Application\Model;

use Application\Model\Base\Division as BaseDivision;
use Address\Model\Country;

class Division extends BaseDivision
{
    protected $country;
    
    public function exchangeArray(array &$data)
    {
        parent::exchangeArray($data);

        $this->country = new Country($this);
        $this->country->exchangeArray($data);
    }

    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    public function getCountry()
    {
        return $this->country;
    }
}
