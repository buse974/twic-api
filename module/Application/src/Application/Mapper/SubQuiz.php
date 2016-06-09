<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use \Zend\Db\Sql\Expression;

class SubQuiz extends AbstractMapper
{
    public function checkFinish($id)
    {
        $select = $this->tableGateway->getSql()->select();
    
        $select->columns([])
            ->join('sub_question', 'sub_question.sub_quiz_id=sub_quiz.id', ['id'])
            ->where(array('sub_quiz.id' => $id))
            ->where(array('sub_question.answered_date IS NULL'));
    
        $res =  $this->selectWith($select);
        if($res->count() === 0) {
            $update = $this->tableGateway->getSql()->update();
            $update->set(['end_date' => new Expression('UTC_TIMESTAMP()')])
                ->where(['sub_quiz.id' => $id]);
            
            $this->updateWith($update);
            return true;
        }
        
        return false;
    }
    
    /**
     * 
     * @param integer $id
     * @param integer $submission_id
     * @param integer $user_id
     * @param boolean $is_finish
     * 
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getList($id = null, $submission_id = null, $user_id = null, $is_finish = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns([
            'id', 
            'poll_id',
            'sub_quiz$start_date' => new Expression('DATE_FORMAT(sub_quiz.start_date, "%Y-%m-%dT%TZ")'),
            'sub_quiz$end_date' => new Expression('DATE_FORMAT(sub_quiz.end_date, "%Y-%m-%dT%TZ")'),
            'user_id',
            'submission_id',
            'grade']);
        
        if(null !== $id) {
            $select->where(array('sub_quiz.id' => $id));
        }
        if(null !== $submission_id) {
            $select->where(array('sub_quiz.submission_id' => $submission_id));
        }
        if(null !== $user_id) {
            $select->where(array('sub_quiz.user_id' => $user_id));
        }
        if($is_finish===true) {
            $select->where(array('sub_quiz.end_date IS NOT NULL'));
        }
        if($is_finish===false) {
            $select->where(array('sub_quiz.end_date IS NULL'));
        }
    
        return  $this->selectWith($select);
    }
    
    public function get($id)
    {
        $select = $this->tableGateway->getSql()->select();
    
        $select->columns([
            'id',
            'poll_id',
            'sub_quiz$start_date' => new Expression('DATE_FORMAT(sub_quiz.start_date, "%Y-%m-%dT%TZ")'),
            'sub_quiz$end_date' => new Expression('DATE_FORMAT(sub_quiz.end_date, "%Y-%m-%dT%TZ")'),
            'user_id',
            'submission_id',
            'grade'])
            ->where(array('sub_quiz.id' => $id));

        return  $this->selectWith($select);
    }

}