<?php

namespace Application\Model;

use Application\Model\Base\ItemGrading as BaseItemGrading;

class ItemGrading extends BaseItemGrading
{
    protected $letter;
    protected $assignmentId;

    public function setLetter($letter)
    {
        $this->letter = $letter;

        return $this;
    }

    public function getLetter()
    {
        return $this->letter;
    }

    public function getAssignmentId()
    {
        return $this->assignmentId;
    }
    
     public function setAssignmentId($assignmentId)
    {
        $this->assignmentId = $assignmentId;

        return $this;
    }
}
