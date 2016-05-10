<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class SubQuiz extends AbstractMapper
{
    public function checkFinish($sub_quiz_id)
    {
        $select = $this->tableGateway->getSql()->select();
    
        $select->columns([])
            ->join('sub_question', 'sub_question.sub_quiz_id=sub_quiz.id', ['id'])
            ->where(array('sub_quiz.id' => $sub_quiz_id))
            ->where(array('sub_question.answered_date IS NULL'));
    
        $res =  $this->selectWith($select);
        if($res->count() === 0) {
            $update = $this->tableGateway->getSql()->update();
            $update->set(['end_date = UTC_TIMESTAMP()'])
                ->where(['sub_quiz.id' => $sub_quiz_id]);
            
                return true;
        }
        
        return false;
    }
    
}