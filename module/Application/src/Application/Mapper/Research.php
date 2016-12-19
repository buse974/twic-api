<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class Research extends AbstractMapper
{
    /**
     * Get research list.
     *
     * @param string $string
     * @param bool $is_sadmin_admin
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getList($string, $is_sadmin_admin, $user_id)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('id', 'firstname', 'lastname', 'nickname', 'avatar', 'category', 'role'))
                ->where(array('(firstname LIKE ?' => '%'.$string.'%'))
                ->where(array('lastname LIKE ?' => '%'.$string.'%'), \Zend\Db\Sql\Predicate\Predicate::OP_OR)
                ->where(array('nickname LIKE ?)' => '%'.$string.'%'), \Zend\Db\Sql\Predicate\Predicate::OP_OR)
                ->order(array('facette', 'firstname'))
                ->quantifier('distinct');

        if ($is_sadmin_admin === false) {
            $select->join(['ou' => 'organization_user'], 'ou.user_id=research.user_id', [], $select::JOIN_LEFT)
                ->join('course', 'course.id=research.course_id', [], $select::JOIN_LEFT)
                ->join('program', 'program.id=course.program_id', [], $select::JOIN_LEFT)
                ->join('school', 'school.id=ou.organization_id OR school.id=research.school_id OR school.id=program.school_id', [])
                ->join(['co' => 'circle_organization'], 'co.organization_id=school.id', [])
                ->join('circle_organization', 'circle_organization.circle_id=co.circle_id', [])
                ->join('organization_user', 'organization_user.organization_id=circle_organization.organization_id', [])
                ->where(['organization_user.user_id' => $user_id]);
        }
                
        return $this->selectWith($select);
    }
}
