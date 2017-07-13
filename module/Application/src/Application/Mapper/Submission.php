<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class Submission extends AbstractMapper
{
    
    public function get($id = null, $item_id = null, $user_id = null)
    {
        $select = $this->tableGateway->getSql()->select();
            
                if($user_id !== null){
                    $select->join('item_user', 'item_user.submission_id=submission.id', [])
                            ->where(['item_user.user_id' => $user_id]);
                }
                if($item_id !== null){
                    $select->where(['submission.item_id' => $item_id]);
                }
                if($id !== null){
                    $select->where(['submission.id' => $id]);
                }
                
                $select->quantifier('DISTINCT');

          
            return $this->selectWith($select);

    }
}