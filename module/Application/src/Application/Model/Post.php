<?php

namespace Application\Model;

use Application\Model\Base\Post as BasePost;

class Post extends BasePost
{
    protected $docs;
    protected $last_date;
    protected $nbr_comments;
    protected $is_liked;
    protected $nbr_likes;
    
    public function setDocs($docs)
    {
        $this->docs = $docs;
    
        return $this;
    }
    
    public function getDocs()
    {
        return $this->docs;
    }
    
    public function setLastDate($last_date)
    {
        $this->last_date = $last_date;
    
        return $this;
    }
    
    public function getLastDate()
    {
        return $this->last_date;
    }
    
    public function setNbrComments($nbr_comments)
    {
        $this->nbr_comments = $nbr_comments;
    
        return $this;
    }
    
    public function getNbrComments()
    {
        return $this->nbr_comments;
    }
    
    public function setNbrLikes($nbr_likes)
    {
        $this->nbr_likes = $nbr_likes;
    
        return $this;
    }
    
    public function getNbrLikes()
    {
        return $this->nbr_likes;
    }
    
    public function setIsLiked($is_liked)
    {
        $this->is_liked = $is_liked;
    
        return $this;
    }
    
    public function getIsLiked()
    {
        return $this->is_liked;
    }
    
}