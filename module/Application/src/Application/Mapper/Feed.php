<?php
namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\Expression;


class Feed extends AbstractMapper
{
    public function getList($user)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id','content','user_id','link','video','picture','document',
            'feed$created_date' => new Expression('DATE_FORMAT(created_date, "%Y-%m-%dT%TZ")')
        ))
            ->join('user', 'user.id=feed.user_id', array('id','firstname','lastname','avatar'))
            ->join('school', 'school.id=user.school_id', array('id','name','short_name','logo'), $select::JOIN_LEFT)
            ->where(array('feed.deleted_date IS NULL'))
            ->where(array('feed.user_id' => $user))
            ->order(array('feed.id DESC'));
        
        return $this->selectWith($select);
    }
}
