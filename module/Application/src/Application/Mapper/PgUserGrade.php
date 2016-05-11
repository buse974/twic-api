<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\Sql\Predicate\Predicate;

class PgUserGrade extends AbstractMapper
{
     public function getProcessedGrades($submission){
        
        $having = new \Zend\Db\Sql\Having();
        $having->expression('COUNT(DISTINCT pg_user_grade.pg_id) = COUNT(DISTINCT submission_pg.user_id)',[]);
        
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['pg_user_grade$grade' => new Expression('AVG(pg_user_grade.grade)')])
           ->join('submission','pg_user_grade.submission_id = submission.id',[])
           ->join('submission_pg','submission_pg.submission_id = pg_user_grade.submission_id',[])
           ->join('submission_user','submission_user.submission_id = pg_user_grade.submission_id',['pg_user_grade$user_id' => 'user_id'])
           ->join('item','submission.item_id = item.id',[])
           ->join('opt_grading','opt_grading.item_id = item.id',[])
           ->where(['pg_user_grade.submission_id' => $submission])
           ->where(['( submission_user.overwritten IS NULL '])
           ->where([' submission_user.overwritten = FALSE )'], Predicate::OP_OR )
           ->where(['opt_grading.mode' => 'average'])
           ->group(['submission_user.user_id'])
           ->having($having); 
         
        return $this->selectWith($select);
    }
}