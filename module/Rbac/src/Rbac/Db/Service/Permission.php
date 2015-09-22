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
    {}

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
        $m_permission = $this->getModel()->setLibelle($permission);
        
        if ($this->getMapper()->insert($m_permission) <= 0) {
            throw new \Exception('error insert');
        }
        
        $this->getServiceRbac()->createRbac();
        
        return $this->getMapper()->getLastInsertValue();
    }

    /**
     * Delete Permission by libelle
     *
     * @invokable
     *
     * @param string $libelle            
     *
     * @return int
     */
    public function delete($libelle)
    {
        $ret = array();
        if (! is_array($libelle)) {
            $libelle = array($libelle);
        }
        
        foreach ($libelle as $i) {
            $m_libelle = $this->getModel();
            $m_libelle->setLibelle($i);
            $ret[$i] = $this->getMapper()->delete($m_libelle);
        }
        
        $this->getServiceRbac()->createRbac();
        
        return $ret;
    }

    /**
     * @invokable
     *
     * @param integer $id            
     * @param integer $permission            
     *
     */
    public function update($id, $permission)
    {
        $m_permission = $this->getModel()
            ->setId($id)
            ->setLibelle($permission);
        
        $ret = $this->getMapper()->update($m_permission);
        
        if ($ret > 0) {
            $this->getServiceRbac()->createRbac();
        }
        
        return $ret;
    }

    public function getListByRole($role)
    {
        return $this->getMapper()->getListByRole($role);
    }

    /**
     * Get permission list.
     *
     * @invokable
     *
     * @param string $filter            
     * @param string $search            
     * @param int $roleId            
     *
     * @return array
     */
    public function getList($filter = null, $roleId = null, $search = null)
    {
        $mapper = $this->getMapper();
        $res = $mapper->usePaginator($filter)->getList($filter, $roleId, $search);
        
        return array('list' => $res,'count' => $mapper->count());
    }

    /**
     *
     * @return \Rbac\Service\Rbac
     */
    public function getServiceRbac()
    {
        return $this->getServiceLocator()->get('rbac.service');
    }
}
