<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class Program extends AbstractMapper
{
    public function get($id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'name', 'level', 'sis', 'year'))
            ->where(array('program.id' => $id));

        return $this->selectWith($select);
    }

    public function getList($user_program, $all = false, $search = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array(
            'id',
            'name',
            'level',
            'sis',
            'year',
        ));

        $sub_select = $this->getMapperUser()->tableGateway->getSql()->select();
        $sub_select->columns(array(
                'school_id',
            ))->where(array(
                'user.id' => $user_program,
            ));
        $select->where(array(
                'school_id' => $sub_select,
            ));

        if ($all === false) {
            $select->join('program_user_relation', 'program_user_relation.program_id=program.id', array())
                    ->where(array('program_user_relation.user_id' => $user_program));
        }

        if ($search !== null) {
            $select->where(array(' ( program.name LIKE ? ' => $search.'%'))
                   ->where(array('program.sis LIKE ? ) ' => $search.'%'), 'OR');
        }

        $select->where(array('program.deleted_date IS NULL'));
        return $this->selectWith($select);
    }

    /**
     * @return \Application\Mapper\User
     */
    public function getMapperUser()
    {
        return $this->getServiceLocator()->get('app_mapper_user');
    }
}
