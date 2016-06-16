<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class PollQuestion extends AbstractMapper
{
    /**
     * Get Last parent id.
     *
     * @param number $question
     *
     * @return number
     */
    public function selectLastParentId($poll)
    {
        $req = 'SELECT id FROM poll_question WHERE id NOT IN (SELECT parent_id FROM poll_question WHERE parent_id IS NOT null) AND poll_id = :poll';
        $res = $this->selectPdo($req, array(':poll' => $poll));

        return ($res->count() > 0) ? $res->current()->getId() : null;
    }
}
