<?php

namespace Application\Model;

use Application\Model\Base\Post as BasePost;

class Post extends BasePost
{
    protected $docs;
    
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