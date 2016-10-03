<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\Expression;
use Dal\Db\Sql\Select;
use Zend\Db\Sql\Predicate\Predicate;

class Post extends AbstractMapper
{
    public function getList($me_id, $page_id = null, $organization_id = null, $user_id = null, $course_id = null, $parent_id = null)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $nbr_comments = $this->tableGateway->getSql()->select();
        $nbr_comments->columns(['nbr_comments' => new Expression('COUNT(true)')])->where(['post.parent_id=`post$id` AND post.deleted_date IS NULL']);
        $nbr_likes = new Select('post_like');
        $nbr_likes->columns(['nbr_thanks' => new Expression('COUNT(true)')])->where(['post_like.post_id=`post$id` AND post_like.is_like IS TRUE']);
        $is_liked = new Select('post_like');
        $is_liked->columns(['is_liked' => new Expression('COUNT(true)')])->where(['post_like.post_id=`post$id` AND post_like.is_like IS TRUE AND post_like.user_id=?' => $me_id]);
        
        
        $columns = [
            'post$id' => new Expression('post.id'),
            'content',
            'link',
            'picture',
            'name_picture',
            'link_title',
            'link_desc',
            'user_id',
            'organization_id',
            'page_id',
            't_user_id',
            't_organization_id',
            't_page_id',
            't_course_id',
            'post$created_date' => new Expression('DATE_FORMAT(post.created_date, "%Y-%m-%dT%TZ")'),
            'post$updated_date' => new Expression('DATE_FORMAT(post.updated_date, "%Y-%m-%dT%TZ")'),
            'post$nbr_comments' => $nbr_comments,
            'post$is_liked' => $is_liked,
            'post$nbr_likes' => $nbr_likes,
        ];
        
        
        if($organization_id === null && $user_id === null && $course_id === null && $parent_id === null && $page_id === null)  {
            $columns['post$last_date'] = new Expression('DATE_FORMAT(MAX(post_subscription.last_date), "%Y-%m-%dT%TZ")');
            $select->columns($columns)
                ->join('post_subscription', 'post_subscription.post_id=post.id', [], $select::JOIN_LEFT)
                ->join('subscription', 'subscription.libelle=post_subscription.libelle', [], $select::JOIN_LEFT)
                ->where(['( post.parent_id IS NULL '])
                ->where(['  (subscription.user_id = ? ' => $me_id])
                ->where(['  post.user_id = ?))' => $me_id], Predicate::OP_OR)
                ->order(['post$last_date' => 'DESC', 'post.id' => 'DESC'])
                ->group('post.id');
        } else {
            $select->columns($columns)->order([ 'post.id' => 'DESC']);
            if(null !== $organization_id) {
                $select->where(['post.parent_id IS NULL'])->where(['post.t_organization_id' => $organization_id]);
            }
            if(null !== $user_id) {
                $select->where(['post.parent_id IS NULL'])->where(['post.t_user_id' => $user_id]);
            }
            if(null !== $course_id) {
                $select->where(['post.parent_id IS NULL'])->where(['post.t_course_id' => $course_id]);
            }
            if(null !== $parent_id) {
                $select->where(['post.parent_id' => $parent_id]);
            }
            if(null !== $page_id) {
                $select->where(['post.parent_id IS NULL'])->where(['post.t_page_id' => $page_id]);
            }
        }
        
        $select->join('user','user.id = post.user_id',['id', 'firstname', 'lastname', 'nickname', 'avatar'])
                ->join('school','user.school_id = school.id',['id', 'short_name', 'logo'])
                ->where(['post.deleted_date IS NULL']);
        return $this->selectWith($select);
    }
    
    public function get($id) 
    {
        $select = $this->tableGateway->getSql()->select();
        
        $nbr_comments = $this->tableGateway->getSql()->select();
        $nbr_comments->columns(['nbr_comments' => new Expression('COUNT(true)')])->where(['post.parent_id=`post$id` AND post.deleted_date IS NULL']);
        $nbr_likes = new Select('post_like');
        $nbr_likes->columns(['nbr_thanks' => new Expression('COUNT(true)')])->where(['post_like.post_id=`post$id` AND post_like.is_like IS TRUE']);
        $is_liked = new Select('post_like');
        $is_liked->columns(['is_liked' => new Expression('COUNT(true)')])->where(['post_like.post_id=`post$id` AND post_like.is_like IS TRUE AND post_like.user_id=?' => $me_id]);

        $columns = [
            'post$id' => new Expression('post.id'),
            'content',
            'link',
            'picture',
            'name_picture',
            'link_title',
            'link_desc',
            'user_id',
            'organization_id',
            'page_id',
            't_user_id',
            't_organization_id',
            't_page_id',
            't_course_id',
            'post$created_date' => new Expression('DATE_FORMAT(post.created_date, "%Y-%m-%dT%TZ")'),
            'post$updated_date' => new Expression('DATE_FORMAT(post.updated_date, "%Y-%m-%dT%TZ")'),
            'post$nbr_comments' => $nbr_comments,
            'post$is_liked' => $is_liked,
            'post$nbr_likes' => $nbr_likes,
        ];
        
        $select->columns($columns)->where(['post.id', $id])
            ->order([ 'post.id' => 'DESC']);
    }

}
    