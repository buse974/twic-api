<?php

namespace Application\Model;

use Application\Model\Base\PostSubscription as BasePostSubscription;

class PostSubscription extends BasePostSubscription
{
    const ACTION_CREATE='create';
    const ACTION_UPDATE='update'; 
    const ACTION_COM='com'; 
    const ACTION_LIKE='like';
    const ACTION_TAG='tag';
    
    protected $user;
    protected $post;
    
    public function exchangeArray(array &$data)
    {
        parent::exchangeArray($data);
    
        $this->user = $this->requireModel('app_model_user', $data);
        $this->post = $this->requireModel('app_model_post', $data);
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
    
    public function getPost()
    {
        return $this->post;
    }
    
    public function setPost($post)
    {
        $this->post = $post;
    
        return $this;
    }
}