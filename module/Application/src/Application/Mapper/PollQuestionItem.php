<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class PollQuestionItem extends AbstractMapper
{
    /**
     * Get Last parent id.
     *
     * @param number $question_item
     *
     * @return number
     */
    public function selectLastParentId($question)
    {
        $req = 'SELECT id FROM poll_question_item WHERE id NOT IN (SELECT parent_id FROM poll_question_item WHERE parent_id IS NOT null) AND poll_question_id = :question';
        $res = $this->selectPdo($req, array(':question' => $question));
    
        return (($res->count() > 0) ? $res->current()->getId() : null);
    }
}