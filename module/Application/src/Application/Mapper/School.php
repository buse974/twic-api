<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * School  
 */
namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\NotIn;

class School extends AbstractMapper
{

    /**
     * Execute Request Get Custom
     *
     * @param string $libelle            
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getCustom($libelle)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id','libelle','custom'))
            ->where(array('school.libelle' => $libelle));
        
        return $this->selectWith($select);
    }

    /**
     * Get school by id.
     *
     * @param int $school            
     *
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function get($school)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id','name','next_name','short_name','logo','describe','website','background','phone','contact','address_id', 'custom', 'libelle'))
            ->join(array('school_user' => 'user'), 'school_user.id=school.contact_id', array('id','firstname','lastname','status','email','birth_date','position','interest','avatar'), $select::JOIN_LEFT)
            ->join(array('school_address' => 'address'), 'school.address_id = school_address.id', array('school_address!id' => 'id','street_no','street_type','street_name','floor','door','apartment','building','longitude','latitude','timezone'), $select::JOIN_LEFT)
            ->join(array('school_address_division' => 'division'), 'school_address_division.id=school_address.division_id', array('school_address_division!id' => 'id','name'), $select::JOIN_LEFT)
            ->join(array('school_address_city' => 'city'), 'school_address_city.id=school_address.city_id', array('school_address_city!id' => 'id','name'), $select::JOIN_LEFT)
            ->join(array('school_address_country' => 'country'), 'school_address_country.id=school_address.country_id', array('school_address_country!id' => 'id','short_name','name'), $select::JOIN_LEFT)
            ->where(array('school.id' => $school))
            ->where(array('school.deleted_date IS NULL'));
        
        return $this->selectWith($select);
    }

    /**
     * Get school list.
     *
     * @param string $filter            
     * @param int $user_id    
     * @param array $exclude        
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getList($filter = null, $search = null, $user_id = null, $exclude = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id','name','next_name','short_name','logo','describe','website','background','phone',  'custom', 'libelle'))
            ->join(array('school_address' => 'address'), 'school.address_id = school_address.id', array('school_address!id' => 'id','street_no','street_type','street_name','floor','door','apartment','building','longitude','latitude','timezone'), $select::JOIN_LEFT)
            ->join(array('school_address_division' => 'division'), 'school_address_division.id=school_address.division_id', array('school_address_division!id' => 'id','name'), $select::JOIN_LEFT)
            ->join(array('school_address_city' => 'city'), 'school_address_city.id=school_address.city_id', array('school_address_city!id' => 'id','name'), $select::JOIN_LEFT)
            ->join(array('school_address_country' => 'country'), 'school_address_country.id=school_address.country_id', array('school_address_country!id' => 'id','short_name','name'), $select::JOIN_LEFT);
        
        if (! empty($search)) {
            $select->where(array('(school.name LIKE ? ' => '%' . $search . '%'))->where(array('school.short_name LIKE ? )' => '%' . $search . '%'), \Zend\Db\Sql\Predicate\Predicate::OP_OR);
        }
        if(null !== $exclude) {
            $select->where(new NotIn('school.id' => $exclude));
        }
        if (null !== $user_id) {
            $select->join('organization_user', 'organization_user.organization_id = school.id', [], $select::JOIN_LEFT)->where(['organization_user.user_id' => $user_id]);
        }
        
        $select->where('school.deleted_date IS NULL');
        
        return $this->selectWith($select);
    }
}
