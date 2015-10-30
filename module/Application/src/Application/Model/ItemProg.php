<?php

namespace Application\Model;

use Application\Model\Base\ItemProg as BaseItemProg;

class ItemProg extends BaseItemProg
{
    protected $users;
    protected $item;
    protected $editable;
    protected $item_assignment;
    protected $item_grade;
    protected $videoconf_archives;
    protected $item_prog_user;
    protected $total;
    protected $started;
    protected $submit;

    public function exchangeArray(array &$data)
    {
        if ($this->isRepeatRelational()) {
            return;
        }

        parent::exchangeArray($data);

        $this->item = $this->requireModel('app_model_item', $data);
        $this->item_assignment = $this->requireModel('app_model_item_assignment', $data);
        $this->item_grade = $this->requireModel('app_model_item_grading', $data);
        $this->item_prog_user = $this->requireModel('app_model_item_prog_user', $data);
    }

    public function getSubmit()
    {
        return $this->submit;
    }
    
    public function setSubmit($submit)
    {
        $this->submit = $submit;
    
        return $this;
    }
    
    public function getStarted()
    {
        return $this->started;
    }
    
    public function setStarted($started)
    {
        $this->started = $started;
    
        return $this;
    }
    
    public function getTotal()
    {
        return $this->total;
    }
    
    public function setTotal($total)
    {
        $this->total = $total;
    
        return $this;
    }
    
    public function getItemProgUser()
    {
        return $this->item_prog_user;
    }

    public function setItemProgUser($item_prog_user)
    {
        $this->item_prog_user = $item_prog_user;

        return $this;
    }

    public function getVideoconfArchives()
    {
        return $this->videoconf_archives;
    }

    public function setVideoconfArchives($videoconf_archives)
    {
        $this->videoconf_archives = $videoconf_archives;

        return $this;
    }

    public function setItemAssignment($item_assignment)
    {
        $this->item_assignment = $item_assignment;

        return $this;
    }

    public function getItemAssignment()
    {
        return $this->item_assignment;
    }

    public function setItemGrade($item_grade)
    {
        $this->item_grade = $item_grade;

        return $this;
    }

    public function getItemGrade()
    {
        return $this->item_grade;
    }

    public function setUsers($users)
    {
        $this->users = $users;

        return $this;
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function setItem($item)
    {
        $this->item = $item;

        return $this;
    }

    public function getItem()
    {
        return $this->item;
    }

    public function getEditable()
    {
        return $this->editable;
    }

    public function setEditable($editable)
    {
        $this->editable = $editable;

        return $this;
    }
}
