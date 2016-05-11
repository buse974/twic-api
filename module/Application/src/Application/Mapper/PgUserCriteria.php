<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\Expression;

class PgUserCriteria extends AbstractMapper
{
   
    
    public function getProcessedGrades($submission, $user = null){
        
        $having = new \Zend\Db\Sql\Having();
        $having->expression('COUNT(DISTINCT pg_user_criteria.criteria_id) = COUNT(DISTINCT criteria.id)',[]);
        
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['pg_id','user_id', 'pg_user_criteria$grade' => new Expression('ROUND(SUM(pg_user_criteria.points) * 100 / SUM(criteria.points))')])
           ->join('submission','pg_user_criteria.submission_id = submission.id',[])
           ->join('item','submission.item_id = item.id',[])
           ->join('grading_policy','item.grading_policy_id = grading_policy.id',[])
           ->join('criteria','grading_policy.id = criteria.grading_policy_id',[])
           ->where(['pg_user_criteria.submission_id' => $submission])
           ->group(['pg_user_criteria.pg_id', 'pg_user_criteria.user_id'])
           ->having($having); 
         
        if(null !== $user){
           $select->where(['pg_user_criteria.pg_id' => $user]);
        }
        return $this->selectWith($select);
    }
    
    
    
    
}