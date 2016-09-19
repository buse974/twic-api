<?php

namespace Application\Model;

use Application\Model\Base\Page as BasePage;

class Page extends BasePage
{
    protected $tags;
    protected $users;
    protected $docs;
    
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
    
}