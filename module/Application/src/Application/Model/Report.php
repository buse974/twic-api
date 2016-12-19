<?php

namespace Application\Model;

use Application\Model\Base\Report as BaseReport;

class Report extends BaseReport
{
    protected $weight;
    protected $reporter;
    protected $user;
    protected $post;
    protected $comment;

    public function exchangeArray(array &$data)
    {
        parent::exchangeArray($data);
        $this->reporter = $this->requireModel('app_model_user', $data, 'reporter');
        $this->user = $this->requireModel('app_model_user', $data);
        $this->post = $this->requireModel('app_model_event', $data);
        $this->comment = $this->requireModel('app_model_event_comment', $data);
    }

    
    public function getWeight()
    {
        return $this->weight;
    }

    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }
    
    public function getReporter()
    {
        return $this->reporter;
    }

    public function setReporter($reporter)
    {
        $this->reporter = $reporter;

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
    
    public function getPost()
    {
        return $this->post;
    }

    public function setPost($post)
    {
        $this->post = $post;

        return $this;
    }
    
    public function getComment()
    {
        return $this->comment;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }
}
