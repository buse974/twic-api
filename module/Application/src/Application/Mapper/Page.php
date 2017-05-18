<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\Sql\Predicate\Predicate;
use Zend\Db\Sql\Select;

class Page extends AbstractMapper
{

    /**
     * Execute Request Get Custom
     *
     * @param  string $libelle
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getCustom($libelle)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id','libelle','custom'))
            ->where(array('page.libelle' => $libelle));

        return $this->selectWith($select);
    }

    /**
     * Get State and role of the current user on page
     *
     * @param  int $user
     * @return \Zend\Db\Sql\Select
     */
    public function getPageStatus($user)
    {
        $select = new Select('page_user');
        $select->columns(['state', 'role', 'page_id'])
            ->where(['user_id' => $user]);

        return $select;
    }

    public function getListId(
      $me,
      $parent_id = null,
      $type = null,
      $start_date = null,
      $end_date = null,
      $member_id = null,
      $strict_dates = false,
      $is_admin = false,
      $search = null,
      $tags = null,
      $children_id = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id', 'title'])
          ->where(['page.deleted_date IS NULL']);

        if(!empty($parent_id)) {
          $select->join('page_relation', 'page_relation.page_id = page.id', ['parent_id'])
            ->where(['page_relation.parent_id' => $parent_id]);
        }
        if(!empty($children_id)) {
          $select->join('page_relation', 'page_relation.parent_id = page.id', ['page_id'])
            ->where(['page_relation.page_id' => $children_id]);
        }
        if (null !== $type) {
          $select->where(['page.type' => $type]);
        }
        if (null !== $member_id) {
          $select->join(['member' => 'page_user'], 'member.page_id = page.id', [])
              ->where(['member.user_id' => $member_id]);
        }

        if (null !== $search) {
          $tags = explode(' ',$search);
          $select->join('page_tag', 'page_tag.page_id = page.id', [], $select::JOIN_LEFT)
            ->join('tag', 'page_tag.tag_id = tag.id', [], $select::JOIN_LEFT)
            ->where(['( page.title LIKE ? ' => '%' . $search . '%'])
            ->where(['tag.name'   => $tags], Predicate::OP_OR)
            ->where(['1)'])
            ->having(['( COUNT(DISTINCT tag.id) = ? OR COUNT(DISTINCT tag.id) = 0 ' => count($tags)])
            ->having([' page.title LIKE ? ) ' => '%' . $search . '%'], Predicate::OP_OR);
        }
        if (!empty($tags)) {
          /*  $select->join('page_tag', 'page_tag.page_id = page.id')
                ->join('tag', 'tag.id = page_tag.tag_id')
                ->where(['tag.name' => $tags])
                ->having(['COUNT(DISTINCT tag.id) = ?' => count($tags)]);*/
        }
        if (null !== $start_date && null !== $end_date) {
            $select->where(['( page.start_date BETWEEN ? AND ? ' => [$start_date,$end_date]])
                ->where(['page.end_date BETWEEN ? AND ?  ' => [$start_date,$end_date]], Predicate::OP_OR)
                ->where(['( page.start_date < ? AND page.end_date > ? ) ) ' => [$start_date,$end_date]], Predicate::OP_OR);
        } else {
            if (null !== $start_date) {
                $paramValue = $strict_dates ? 'page.start_date >= ?' : 'page.end_date >= ?';
                $select->where([$paramValue => $start_date]);
            }
            if (null !== $end_date) {
                $paramValue = $strict_dates ? 'page.end_date <= ?' : 'page.start_date <= ?';
                $select->where([$paramValue => $end_date]);
            }
        }
        if ($is_admin === false) {
            $select->join('page_user', 'page_user.page_id = page.id', [], $select::JOIN_LEFT)
                ->where(["( page.confidentiality = 0 "])
                ->where([" page_user.user_id = ? )" => $me], Predicate::OP_OR);

            /*$select->join('user', 'page.owner_id=user.id', [])
                ->join(['co' => 'circle_organization'], 'co.organization_id=user.organization_id', [])
                ->join('circle_organization', 'circle_organization.circle_id=co.circle_id', [])
                ->join(['circle_page_user' => 'page_user'], 'circle_page_user.page_id=circle_organization.organization_id', [])
                ->where(['circle_page_user.user_id' => $me]);*/
        }
        $select->order(['page.start_date' => 'DESC'])
            ->group('page.id');

        return $this->selectWith($select);
    }

    public function get($me, $id = null, $parent_id = null, $type = null, $is_admin = false)
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
                'user_id',
                'owner_id',
                'conversation_id',
                'page$start_date' => new Expression('DATE_FORMAT(page.start_date, "%Y-%m-%dT%TZ")'),
                'page$end_date' => new Expression('DATE_FORMAT(page.end_date, "%Y-%m-%dT%TZ")')
            ]
        )->join(['state' => $this->getPageStatus($me)], 'state.page_id = page.id', [
          'page$state' => 'state',
          'page$role' => 'role'
        ], $select::JOIN_LEFT)
         ->join(['p_user' => 'user'], 'p_user.id = page.owner_id', ['id', 'firstname', 'lastname', 'avatar', 'ambassador'], $select::JOIN_LEFT)
         ->join(['page_address' => 'address'], 'page.address_id = page_address.id', ['page_address!id' => 'id','street_no','street_type','street_name','floor','door','apartment','building','longitude','latitude','timezone'], $select::JOIN_LEFT)
         ->join(['page_address_division' => 'division'], 'page_address_division.id=page_address.division_id', ['page_address_division!id' => 'id','name'], $select::JOIN_LEFT)
         ->join(['page_address_city' => 'city'], 'page_address_city.id=page_address.city_id', ['school_address_city!id' => 'id','name'], $select::JOIN_LEFT)
         ->join(['page_address_country' => 'country'], 'page_address_country.id=page_address.country_id', ['page_address_country!id' => 'id','short_name','name'], $select::JOIN_LEFT);

        if (null !== $id) {
            $select->where(['page.id' => $id]);
        }
        if (null !== $type) {
            $select->where(['page.type' => $type]);
        }
        if ($is_admin === false) {
            $select->join('page_user', 'page_user.page_id = page.id', [], $select::JOIN_LEFT)
                ->where(["( page.confidentiality = 0 "])
                ->where([" page_user.user_id = ? )" => $me], Predicate::OP_OR);

            /*$select->join('user', 'page.owner_id=user.id', [])
                ->join(['co' => 'circle_organization'], 'co.organization_id=user.organization_id', [])
                ->join('circle_organization', 'circle_organization.circle_id=co.circle_id', [])
                ->join(['circle_page_user' => 'page_user'], 'circle_page_user.page_id=circle_organization.organization_id', [])
                ->where(['page.deleted_date IS NULL'])
                ->where(['circle_page_user.user_id' => $me]);*/
        }
        $select->group('page.id');

        return $this->selectWith($select);
    }

    public function getIdByItem($item_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id'])
          ->join('item', 'page.id=item.page_id', [])
          ->where(['item.id' => $item_id]);

        return $this->selectWith($select);
    }

}
