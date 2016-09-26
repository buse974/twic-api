<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\Expression;
use Dal\Db\Sql\Select;
use Zend\Db\Sql\Predicate\Predicate;

class Post extends AbstractMapper
{
    public function getList($me_id, $page_id = null)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $nbr_comments = $this->tableGateway->getSql()->select();
        $nbr_comments->columns(['nbr_comments' => new Expression('COUNT(true)')])
            ->where(['post.parent_id=`post$id` AND post.deleted_date IS NULL']);
        
        $nbr_likes = new Select('post_like');
        $nbr_likes->columns(['nbr_thanks' => new Expression('COUNT(true)')])
            ->where(['post_like.post_id=`post$id` AND post_like.is_like IS TRUE']);
        
        $is_liked = new Select('post_like');
        $is_liked->columns(['is_liked' => new Expression('COUNT(true)')])
            ->where(['post_like.post_id=`post$id` AND post_like.is_like IS TRUE AND post_like.user_id=?' => $me_id]);
        
        //deleted_date,
        //parent_id,
        //t_page_id, 
        //t_organization_id, 
        //t_user_id, 
        //t_course_id,
        
        $columns = [
            'post$id' => new Expression('post.id'),'content', 'link', 'picture', 'name_picture', 'link_title', 'link_desc', 'user_id',
            'post$created_date' => new Expression('DATE_FORMAT(post.created_date, "%Y-%m-%dT%TZ")'),
            'post$updated_date' => new Expression('DATE_FORMAT(post.updated_date, "%Y-%m-%dT%TZ")'), 
            'post$nbr_comments' => $nbr_comments,
            'post$is_liked' => $is_liked,
            'post$nbr_likes' => $nbr_likes,
        ];
        
       /* $columns['roadmap$last_date'] = new Expression(
            'GREATEST(
                IF(ISNULL(hashtag.created_date),0,MAX(hashtag.created_date)),
                IF(ISNULL(thank.created_date),0,MAX(thank.created_date)),
                IF(ISNULL(roadmap_com.created_date),0,MAX(roadmap_com.created_date)),
                IF(ISNULL(roadmap.created_date),0,MAX(roadmap.created_date)))'
            );
        $select->order(['roadmap$last_date' => 'DESC', 'roadmap.id' => 'DESC']);
        */
        $select->columns($columns)
            ->join('contact', 'contact.contact_id=post.t_user_id', [], $select::JOIN_LEFT)
            ->join('page_user', 'page_user.page_id=post.t_page_id', [], $select::JOIN_LEFT)
            ->join('organization_user', 'organization_user.organization_id=post.t_organization_id', [], $select::JOIN_LEFT)
            ->join('course_user_relation', 'course_user_relation.course_id=post.t_course_id', [], $select::JOIN_LEFT)
            
            
            
            ->where(['( post.parent_id IS NULL ']) 
            ->where(['( post.user_id = ?' => $me_id]) // Moi
            ->where(['  contact.user_id = ?' => $me_id], Predicate::OP_OR)  // Contact
            ->where(['  page_user.user_id = ?' => $me_id], Predicate::OP_OR)  // Page
            ->where(['  organization_user.user_id = ?' => $me_id], Predicate::OP_OR)  // Organization
            ->where(['  course_user_relation.user_id = ? ))' => $me_id], Predicate::OP_OR)  // Course
            
            
            
          //->where(['( hashtag.type="@" AND account.id = ? )' => $me], Predicate::OP_OR)
          //  ->where(['( thank.thank IS TRUE AND follow_thank.account_id = ? )' => $me_id], Predicate::OP_OR)
          //  ->where(['  follow_com.account_id = ? )' => $me_id], Predicate::OP_OR);
            
            
            
            
            
            
            ->group('post.id');
        
        return $this->selectWith($select);
    }
}