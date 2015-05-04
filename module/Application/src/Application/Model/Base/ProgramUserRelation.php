<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class ProgramUserRelation extends AbstractModel
{
    protected $program_id;
    protected $user_id;

    protected $prefix = 'program_user_relation';

    public function getProgramId()
    {
        return $this->program_id;
    }

    public function setProgramId($program_id)
    {
        $this->program_id = $program_id;

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
