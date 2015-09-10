<?php
namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\Sql\Ddl\Column\Integer;


class Feed extends AbstractMapper
{
    public function getList($contact, $me, $ids = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id','content','user_id','link','link_title','link_desc','video','picture','document','name_picture','name_document',
            'feed$created_date' => new Expression('DATE_FORMAT(feed.created_date, "%Y-%m-%dT%TZ")')
        ))
            ->join('user', 'user.id=feed.user_id', array('id','firstname','lastname','avatar'))
            /*->join('like', 'feed.id=like.feed_id', array('feed$nb_like' => new Expression('COUNT(like.feed_id)'),
                'feed$is_like' => new Expression('MAX(IF(like.user_id = '.$me.', 1, 0))')), $select::JOIN_LEFT)*/
            ->join('school', 'school.id=user.school_id', array('id','name','short_name','logo'), $select::JOIN_LEFT)
            ->where(array('feed.deleted_date IS NULL'))
            ->group('feed.id')
            ->order(array('feed.id DESC'));
        
            if(null !== $ids) {
                $select->where(array('feed.id' => $ids));
            } else {
                $select->where(array('feed.user_id' => $contact));
            }
            
        return $this->selectWith($select);
    }
}
