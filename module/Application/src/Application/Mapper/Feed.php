<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\Expression;

class Feed extends AbstractMapper
{
    public function getList($contact, $me, $ids = null, $is_sadmin = false)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'content', 'user_id', 'link', 'link_title', 'link_desc', 'video', 'picture', 'document', 'name_picture', 'name_document',
            'feed$created_date' => new Expression('DATE_FORMAT(feed.created_date, "%Y-%m-%dT%TZ")'),
        ))
            ->join('user', 'user.id=feed.user_id', array('id', 'firstname', 'lastname', 'avatar'))
            ->join('school', 'school.id=user.school_id', array('id', 'name', 'short_name', 'logo'), $select::JOIN_LEFT)
            ->group('feed.id')
            ->order(array('feed.id DESC'));
        if(true !== $is_sadmin){
            $select->where(array('feed.deleted_date IS NULL'));
        }
        if (null !== $ids) {
            $select->where(array('feed.id' => $ids));
        } else {
            $select->where(array('feed.user_id' => $contact));
        }

        return $this->selectWith($select);
    }
}
