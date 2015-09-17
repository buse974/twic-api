<?php

namespace Application\Model;

use Application\Model\Base\Feed as BaseFeed;

class Feed extends BaseFeed
{
    protected $user;
    protected $nb_comment;
    protected $nb_like;
    protected $is_like;

    public function exchangeArray(array &$data)
    {
        parent::exchangeArray($data);

        $this->user = $this->requireModel('app_model_user', $data);
    }
    
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
    
    public function getUser() 
    {
        return $this->user;
    }

    public function setUser($user) 
    {
        $this->user = $user;
        
        return $this;
    }
    
    public function getNbComment()
    {
        return $this->nb_comment;
    }
     
    public function setNbComment($nb_comment)
    {
        $this->nb_comment = $nb_comment;
    
        return $this;
    }
}
