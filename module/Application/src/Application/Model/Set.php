<?php

namespace Application\Model;

use Application\Model\Base\Set as BaseSet;

class Set extends BaseSet
{
    protected $groups;

    public function getGroups()
    {
        return $this->groups;
    }

    public function setGroups($groups)
    {
        $this->groups = $groups;

        return $this;
    }
}
