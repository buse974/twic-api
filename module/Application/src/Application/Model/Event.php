<?php

namespace Application\Model;

use Application\Model\Base\Event as BaseEvent;

class Event extends BaseEvent
{
    protected $nb_like;
    protected $is_like;
    
    public function getIsLike() 
    {
        return $this->is_like;
    }

    public function setIsLike($is_like) 
    {
        $this->is_like = $is_like;
        
        return $this;
    }

    public function getNbLike() 
    {
        return $this->nb_like;
    }

    public function setNbLike($nb_like) 
    {
        $this->nb_like = $nb_like;
        
        return $this;
    }
}