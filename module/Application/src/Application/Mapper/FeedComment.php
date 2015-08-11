<?php
namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class FeedComment extends AbstractMapper
{

    public function getList($feed)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id','content','created_date'))
            ->join('user', 'user.id=feed_comment.user_id', array('id','firstname','lastname','avatar'))
            ->join('school', 'school.id=user.school_id', array('id','name','short_name','logo'), $select::JOIN_LEFT)
            ->where(array('feed_comment.deleted_date IS NULL'))
            ->where(array('feed_comment.feed_id' => $feed))
            ->order(array('feed_comment.id DESC'));
        
        return $this->selectWith($select);
    }
}
