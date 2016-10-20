<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class PostSubscription extends AbstractMapper
{
    public function getLast($post_id, $user_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id', 'action','libelle', 'last_date', 'sub_post_id'])
            ->join('subscription', 'subscription.libelle=post_subscription.libelle', [])
            ->where(['subscription.user_id' => $user_id])
            ->order(['post_subscription.last_date' => 'DESC'])
            ->limit(1);
        
        return $this->selectWith($select);
    }
}