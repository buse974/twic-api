<?php

namespace Application\Model;

use Application\Model\Base\Connection as BaseConnection;

class Connection extends BaseConnection
{
    protected $avg;
    protected $nbr_session;

    public function getNbrSession()
    {
        return $this->nbr_session;
    }
    
    public function setNbrSession($nbr_session)
    {
        $this->nbr_session = $nbr_session;
    
        return $this;
    }
    
    public function getAvg() 
    {
        return $this->avg;
    }
    
    public function setAvg($avg) 
    {
        $this->avg = $avg;
        
        return $this;
    }
}