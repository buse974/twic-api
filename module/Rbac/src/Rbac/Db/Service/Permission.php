<?php

namespace Rbac\Db\Service;

use Dal\Service\AbstractService;

class Permission extends AbstractService
{
    public function getPermission()
    {
        return $this->getMapper()->getPermissions();
    }

    public function getPermissionByRole($role)
    {
    }

    public function insert($perm)
    {
        $this->getMapper()->insert($perm);

        return $this->getMapper()->getLastInsertValue();
    }
    
    /**
     * Add permission     
     * @invokable
     *
     * @param string $permission
     *
     * @return int
     */
    public function add($permission)
    {
        $m_permission = $this->getModel();

        $m_permission->setLibelle($permission);

        if ($this->getMapper()->insert($m_permission) <= 0) {
            throw new \Exception('error insert');
        }

        return $this->getMapper()->getLastInsertValue();
    }
    
    /**
     * Delete Permission by libelle
     * @invokable
     *  
     * @param string $libelle
     *
     * @return int
     */
    public function delete($libelle)
    {
        $ret = array();
        if (!is_array($libelle)) {
            $libelle = array($libelle);
        }

        foreach ($libelle as $i) {
            $m_libelle = $this->getModel();
            $m_libelle->setLibelle($i);
            $ret[$i] = $this->getMapper()->delete($m_libelle);
        }

        return $ret;
    }     
    
    /**
     * @invokable
     */
    public function update($mPermission)
    {
        return $this->getMapper()->update($mPermission);
    }

    public function getListByRole($role)
    {
        return $this->getMapper()->getListByRole($role);
    }
    
    /**
     * Get permission list.
     *
     * @param string $filter
     * @param string $search
     * @param int $roleId
     * 
     * @invokable
     *
     * @return array
     */
    public function getList($filter = null, $roleId = null, $search = null)
    {
        $mapper = $this->getMapper();
        $res = $mapper->usePaginator($filter)->getList($filter, $roleId, $search);

        return array('list' => $res,
                    'count' => $mapper->count());
    }
}
