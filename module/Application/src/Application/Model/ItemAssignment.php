<?php

namespace Application\Model;

use Application\Model\Base\ItemAssignment as BaseItemAssignment;

class ItemAssignment extends BaseItemAssignment
{
    protected $submission;
    protected $students;
    protected $documents;
    protected $comments;

    public function exchangeArray(array &$data)
    {
        parent::exchangeArray($data);

        $this->submission = $this->module = $this->requireModel('app_model_submission', $data);
    }

    public function setItemProg($submission)
    {
        $this->submission = $submission;

        return $this;
    }

    public function getItemProg()
    {
        return $this->submission;
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
