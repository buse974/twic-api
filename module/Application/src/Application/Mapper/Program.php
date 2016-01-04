<?php
namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class Program extends AbstractMapper
{

    public function get($id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id','name','level','sis','year'))->where(array('program.id' => $id));
        
        return $this->selectWith($select);
    }

    public function getListUser($user)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id','name','level','sis','year'))
            ->join('program_user_relation', 'program_user_relation.program_id=program.id', [])
            ->where(['program_user_relation.user_id' => $user])
            ->where(array('program.deleted_date IS NULL'));
        
        return $this->selectWith($select);
    }

    public function getList($user, $search = null, $school = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id','name','level','sis','year'));
        
        if (null !== $school) {
            $select->where(['school_id' => $school]);
        } else {
            $sub_select = $this->getMapperUser()->tableGateway->getSql()->select();
            $sub_select->columns(array('school_id'))->where(array('user.id' => $user));
            $select->where(['school_id' => $sub_select]);
        }
        
        if ($search !== null) {
            $select->where(array(' ( program.name LIKE ? ' => $search . '%'))->where(array('program.sis LIKE ? ) ' => $search . '%'), 'OR');
        }
        
        $select->where(array('program.deleted_date IS NULL'));
        
        return $this->selectWith($select);
    }

    /**
     *
     * @return \Application\Mapper\User
     */
    public function getMapperUser()
    {
        return $this->getServiceLocator()->get('app_mapper_user');
    }
}
