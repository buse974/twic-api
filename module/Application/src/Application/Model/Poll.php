<?php

namespace Application\Model;

use Application\Model\Base\Poll as BasePoll;

class Poll extends BasePoll
{
    protected $poll_item;

    public function getPollItem()
    {
        return $this->poll_item;
    }

    public function setPollItem($poll_item)
    {
        $this->poll_item = $poll_item;

        return $this;
    }
}
