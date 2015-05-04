<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class IndividualAssignmentUser extends AbstractModel
{
    protected $id;
    protected $question;
    protected $individual_assigment_id;
    protected $user_id;

    protected $prefix = 'individual_assignment_user';

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

    public function getIndividualAssigmentId()
    {
        return $this->individual_assigment_id;
    }

    public function setIndividualAssigmentId($individual_assigment_id)
    {
        $this->individual_assigment_id = $individual_assigment_id;

        return $this;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }
}
