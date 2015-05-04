<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class MessageGroup extends AbstractModel
{
    protected $id;

    protected $prefix = 'message_group';

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}
