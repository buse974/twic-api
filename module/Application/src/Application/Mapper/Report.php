<?php

namespace Application\Mapper;
 
use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\Expression;

class Report extends AbstractMapper
{
    public function getList($treated)
    {
        
        $sub_select = $this->tableGateway->getSql()->select();
        $sub_select->columns(['sub$user_id' => 'user_id','sub$post_id' => 'post_id', 'sub$comment_id' => 'comment_id', 'weight' => new Expression('SUM(IF(validate = 0,0,1))')])
                   ->group(['user_id', 'post_id', 'comment_id']);
        
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id', 'reason', 'description', 'user_id', 'post_id', 'comment_id', 'validate', 'treatment_date', 'treated'])
               ->join(['reporter' => 'user'], 'report.reporter_id = reporter.id', ['id', 'avatar', 'firstname', 'lastname', 'nickname'])
               ->join(['sub' =>$sub_select],
                      'report.user_id = sub$user_id  OR report.post_id = sub$post_id OR report.comment_id = sub$comment_id',
                      ['report$weight' => 'weight']);
        
        if(true === $treated){
            $select->where('report.treatment_date IS NOT NULL');
        }

        return $this->selectWith($select);
    }    
   
    
    
  
}
