<?php

namespace Application\Model;

use Application\Model\Base\ItemAssignment as BaseItemAssignment;

class ItemAssignment extends BaseItemAssignment
{
    protected $item_prog;
    protected $students;
    protected $documents;
    protected $comments;

    public function exchangeArray(array &$data)
    {
        parent::exchangeArray($data);

        $this->item_prog = $this->module = $this->requireModel('app_model_item_prog', $data);
    }

    public function setItemProg($item_prog)
    {
        $this->item_prog = $item_prog;

        return $this;
    }

    public function getItemProg()
    {
        return $this->item_prog;
    }

    public function setStudents($students)
    {
        $this->students = $students;

        return $this;
    }

    public function getStudents()
    {
        return $this->students;
    }

    public function setDocuments($documents)
    {
        $this->documents = $documents;

        return $this;
    }

    public function getDocuments()
    {
        return $this->documents;
    }

    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    public function getComments()
    {
        return $this->comments;
    }
}
