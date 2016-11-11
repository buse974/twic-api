<?php
namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\NotIn;

class Program extends AbstractMapper
{

    /**
     * Request Get Program
     *
     * @param int $id            
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function get($id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id','name','level','sis','year', 'school_id'))->where(array('program.id' => $id));
        
        return $this->selectWith($select);
    }

    /**
     * Request Get List Program By User
     *
     * @param int $user_id            
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListUser($user_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id','name','level','sis','year'))
            ->join('program_user_relation', 'program_user_relation.program_id=program.id', [])
            ->where(['program_user_relation.user_id' => $user_id])
            ->where(array('program.deleted_date IS NULL'));
        
        return $this->selectWith($select);
    }

    /**
     * Request Program Get List
     *
     * @param int $user_id            
     * @param string $search            
     * @param int $school_id            
     * @param bool $is_admin_academic            
     * @param bool $self            
     * @param array $exclude            
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getList($user_id, $search = null, $school_id = null, $is_admin_academic = false, $self = true, $exclude = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id','name','level','sis','year','school_id'));
        
        if (null !== $school_id) {
            $select->where(['school_id' => $school_id]);
        }
        
        if ($self === true) {
            if ($is_admin_academic === true) {
                $select->join('organization_user', 'organization_user.organization_id=program.school_id', [])->where(['organization_user.user_id' => $user_id]);
            } else {
                $select->join('program_user_relation', 'program_user_relation.program_id=program.id', [])->where(['program_user_relation.user_id' => $user_id]);
            }
        }
        if (!empty($exclude)) {
            $select->where(new NotIn('program.id', $exclude));
        }
        if ($search !== null) {
            $select->where(array(' ( program.name LIKE ? ' => $search . '%'))->where(array('program.sis LIKE ? ) ' => $search . '%'), 'OR');
        }
        
        $select->where(array('program.deleted_date IS NULL'));
        
        return $this->selectWith($select);
    }
}
