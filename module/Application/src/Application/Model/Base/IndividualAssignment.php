<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class IndividualAssignment extends AbstractModel
{
    protected $id;
    protected $question;
    protected $item_id;

    protected $prefix = 'individual_assignment';

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getQuestion()
    {
        return $this->question;
    }

    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    public function getItemId()
    {
        return $this->item_id;
    }

    public function setItemId($item_id)
    {
        $this->item_id = $item_id;

        return $this;
    }
}
