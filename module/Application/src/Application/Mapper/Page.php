<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\Sql\Predicate\Predicate;
use Zend\Db\Sql\Select;

class Page extends AbstractMapper
{
    
      /**
     * Get State and role of the current user on page
     * 
     * @param int $user
     * @return \Zend\Db\Sql\Select
     */
    public function getPageStatus($user)
    {
        $select = new Select('page_user');
        $select->columns(['state', 'role','page_id'])
                 ->where(['user_id' => $user]);

        return $select;
    }


    public function getList($me, $id = null, $parent_id = null, $user_id = null, $organization_id = null, $type = null, $start_date = null, $end_date = null, $member_id = null, $strict_dates = false, $is_sadmin_admin = false, $search = null, $tags = null)
    {
        $where = $this->getWhereParams([
            'page.id' => $id,
            'page.page_id' => $parent_id,
            'page.user_id' => $user_id,
            'page.organization_id' => $organization_id,
            'page.type' => $type
        ]);
        
        

        $select = $this->tableGateway->getSql()->select();
        $select->columns(
            [
                'id',
                'title',
                'logo',
                'background',
                'description',
                'confidentiality',
                'admission',
                'location',
                'type', 
                'page$start_date' => new Expression('DATE_FORMAT(page.start_date, "%Y-%m-%dT%TZ")'),
                'page$end_date' => new Expression('DATE_FORMAT(page.end_date, "%Y-%m-%dT%TZ")'),
                'user_id',
                'organization_id',
                'page_id',
            ]
        );
        $select->join(['state' => $this->getPageStatus($me)],'state.page_id = page.id', ['page$state' => 'state', 'page$role' => 'role'], $select::JOIN_LEFT);
       
         if(null !== $parent_id){
            $select
                ->join(['parent' => 'page'],'page.page_id = parent.id', [])
                ->join(['parent_user' => 'page'], 'parent_user.page_id = parent.id', [], $select::JOIN_LEFT)
                ->where(["( parent.confidentiality = 0 "])
                ->where([" parent_user.user_id = ? )" => $me], Predicate::OP_OR);
        }
        else{
            $select->join('page_user', 'page_user.page_id = page.id', [], $select::JOIN_LEFT)
                ->where(["( page.confidentiality = 0 "])
                ->where([" page_user.user_id = ? )" => $me], Predicate::OP_OR);
        }
        $select->where($where);
        if (null !== $member_id) {
            $select->join(['member' => 'page_user'], 'member.page_id = page.id', [])
                    ->where(['member.user_id' => $member_id]);
        }

        if (null !== $search) {
            $select->where(array('page.title LIKE ? ' => '%' . $search . '%'));

        if (null !== $tags) {
            $select->join('page_tag', 'page_tag.page_id = page.id')
                   ->join('tag', 'tag.id = page_tag.tag_id')
                   ->where(['tag.name' => $tags])
                   ->having(['COUNT(DISTINCT tag.id) = ?' => count($tags)]);
        }
        
        if (null !== $start_date && null !== $end_date) {
            $select->where(['( page.start_date BETWEEN ? AND ? ' => [$start_date,$end_date]])
                ->where(['page.end_date BETWEEN ? AND ?  ' => [$start_date,$end_date]], Predicate::OP_OR)
                ->where(['( page.start_date < ? AND page.end_date > ? ) ) ' => [$start_date,$end_date]], Predicate::OP_OR);
        }
        else{
           
            if (null !== $start_date) {
                $paramValue = $strict_dates ? 'page.start_date >= ?' : 'page.end_date >= ?';

                $select->where([$paramValue => $start_date]);
            }
            if (null !== $end_date) {
                $paramValue = $strict_dates ? 'page.end_date <= ?' : 'page.start_date <= ?';

                $select->where([$paramValue => $end_date]);
            }
        }
        
        
        if($is_sadmin_admin === false) {
           $select->join('user', 'page.user_id=user.id', [])
               ->join(['co' => 'circle_organization'], 'co.organization_id=user.school_id', [])
               ->join('circle_organization', 'circle_organization.circle_id=co.circle_id', [])
               ->join('organization_user', 'organization_user.organization_id=circle_organization.organization_id', [])
               ->where(['organization_user.user_id' => $me]);
       }
        $select->order(['page.start_date' => 'DESC'])
               ->group('page.id');
        return $this->selectWith($select);
    }

    protected function getWhereParams($originalParams = [])
    {
        return array_filter($originalParams, function($value) {
            return null !== $value;
        });
    }
    
    public function get($me, $id = null, $parent_id = null, $type = null, $is_sadmin_admin = false)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(
            [
                'id',
                'title',
                'logo',
                'background',
                'description',
                'confidentiality',
                'admission',
                'location',
                'type', 
                'page_id', 
                'user_id',
                'organization_id',
                'page$start_date' => new Expression('DATE_FORMAT(page.start_date, "%Y-%m-%dT%TZ")'),
                'page$end_date' => new Expression('DATE_FORMAT(page.end_date, "%Y-%m-%dT%TZ")')
            ]
        );
        $select->join(['state' => $this->getPageStatus($me)],'state.page_id = page.id', ['page$state' => 'state', 'page$role' => 'role'], $select::JOIN_LEFT);
         if(null !== $id){
            $select->where(array('page.id' => $id));
        }
         if(null !== $parent_id){
            $select
                ->join(['parent' => 'page','page.page_id = parent.id'], [])
                ->join(['parent_user' => 'page_user'], 'parent_user.page_id = parent.page_id', [], $select::JOIN_LEFT)
                ->where(["( parent.confidentiality = 0 "])
                ->where([" parent_user.user_id = ? )" => $me], Predicate::OP_OR);
        }
        else{
            $select->join('page_user', 'page_user.page_id = page.id', [], $select::JOIN_LEFT)
                ->where(["( page.confidentiality = 0 "])
                ->where([" page_user.user_id = ? )" => $me], Predicate::OP_OR);;
        }
        if(null !== $type){
            $select->where(array('page.type' => $type));
        }
        if($is_sadmin_admin === false) {
           $select->join('user', 'page.user_id=user.id', [])
               ->join(['co' => 'circle_organization'], 'co.organization_id=user.school_id', [])
               ->join('circle_organization', 'circle_organization.circle_id=co.circle_id', [])
               ->join('organization_user', 'organization_user.organization_id=circle_organization.organization_id', [])
               ->where(['organization_user.user_id' => $me]);
       }
        $select->group('page.id');
        return $this->selectWith($select);
    }

}
