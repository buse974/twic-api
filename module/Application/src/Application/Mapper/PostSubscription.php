<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class PostSubscription extends AbstractMapper
{
    public function getLast($post_id, $user_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['action', 'last_date', 'sub_post_id', 'user_id'])
            ->join('subscription', 'subscription.libelle=post_subscription.libelle', [])
            ->join('user', 'user.id=post_subscription.user_id', ['id', 'firstname', 'lastname', 'nickname'], $select::JOIN_LEFT)
            ->join('post' , 'post.id=post_subscription.sub_post_id', ['id', 'content', 'organization_id', 'page_id'], $select::JOIN_LEFT)
            ->where(['subscription.user_id' => $user_id])
            ->where(['post_subscription.post_id' => $post_id])
            ->order(['post_subscription.id' => 'DESC'])
            ->limit(1);
        
        return $this->selectWith($select);
    }
    
    public function getLastLite($post_id, $user_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['action', 'last_date', 'sub_post_id', 'user_id'])
            ->join('subscription', 'subscription.libelle=post_subscription.libelle', [])
            ->join('post' , 'post.id=post_subscription.sub_post_id', ['id', 'content', 'organization_id', 'page_id'], $select::JOIN_LEFT)
            ->where(['subscription.user_id' => $user_id])
            ->where(['post_subscription.post_id' => $post_id])
            ->order(['post_subscription.id' => 'DESC'])
            ->limit(1);
    
        return $this->selectWith($select);
    }
}