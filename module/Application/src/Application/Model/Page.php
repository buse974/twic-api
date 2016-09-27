<?php

namespace Application\Model;

use Application\Model\Base\Page as BasePage;

class Page extends BasePage
{
    
    
    const TYPE_EVENT='event';
    const TYPE_GROUP='group';
     
    protected $tags;
    protected $users;
    protected $docs;
    protected $events;
    protected $state;
    protected $role;
    
    public function setUsers($users)
    {
        $this->users = $users;
        
        return $this;
    }
    
    public function getUsers()
    {
        return $this->users;
    }
    
    public function setTags($tags)
    {
        $this->tags = $tags;
    
        return $this;
    }
    
    public function getTags()
    {
        return $this->tags;
    }
    
    public function setDocs($docs)
    {
        $this->docs = $docs;
    
        return $this;
    }
    
    public function getDocs()
    {
        return $this->docs;
    }
    
    public function setEvents($events)
    {
        $this->events = $events;
    
        return $this;
    }
    
    public function getEvents()
    {
        return $this->events;
    }
    
    public function setRole($role)
    {
        $this->role = $role;
    
        return $this;
    }
    
    public function getRole()
    {
        return $this->role;
    }
    
    public function setState($state)
    {
        $this->state = $state;
    
        return $this;
    }
    
    public function getState()
    {
        return $this->state;
    }
    
}