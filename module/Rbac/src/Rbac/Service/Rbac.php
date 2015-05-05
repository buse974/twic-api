<?php

namespace Rbac\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Permissions\Rbac\Role;
use Zend\Permissions\Rbac\Rbac as ZRBac;

class Rbac implements ServiceLocatorAwareInterface
{
    /**
     * @var \Zend\ServiceManager\ServiceManager
     */
    protected $service_locator;

    /**
     * @var \Zend\Permissions\Rbac\Rbac
     */
    protected $rbac;

    /**
     * @var \Zend\Cache\Storage\StorageInterface
     */
    protected $cache;

    /**
     * @param array $options
     */
    public function initialize()
    {
        $this->getRbac();
    }

    /**
     * Check permission.
     *
     * @param array|string $role
     * @param string       $permission
     *
     * @return bool
     */
    public function isGranted($role, $permission)
    {
        if (!is_array($role)) {
            $role = array($role);
        }

        foreach ($role as $r) {
            if ($this->getRbac()->isGranted($r, $permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get Rbac Obj.
     *
     * @return \Zend\Permissions\Rbac\Rbac
     */
    public function getRbac()
    {
        if ($this->rbac === null) {
            if (!$this->getCache()->hasItem('rbac')) {
                $roles = $this->getServiceRole()->getAll()->toArray();
                $rbac = new ZRBac();
                foreach ($roles as $role) {
                    $ar_child = array();
                    if (isset($role['parent'])) {
                        foreach ($role['parent'] as $parent) {
                            if (!$rbac->hasRole($parent['name'])) {
                                $rbac->addRole(new Role($parent['name']));
                            }
                            $ar_child[] = $rbac->getRole($parent['name']);
                        }
                    }
                    if (!$rbac->hasRole($role['name'])) {
                        $rbac->addRole(new Role($role['name']));
                    }
                    $r = $rbac->getRole($role['name']);
                    $rbac->addRole($r, $ar_child);
                    if (isset($role['permission'])) {
                        foreach ($role['permission'] as $p) {
                            $r->addPermission($p['libelle']);
                        }
                    }
                }
                $this->getCache()->setItem('rbac', $rbac);
            } else {
                $rbac = $this->getCache()->getItem('rbac');
            }

            $this->rbac = $rbac;
        }

        return $this->rbac;
    }

    /**
     * @return \Rbac\Db\Service\Role
     */
    public function getServiceRole()
    {
        return $this->getServiceLocator()->get('rbac_service_role');
    }

    /**
     * Set servicelocator.
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->service_locator = $serviceLocator;

        return $this;
    }

    /**
     * Get service locator.
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->service_locator;
    }

    /**
     * Get Storage if define in config.
     *
     * @return \Zend\Cache\Storage\StorageInterface
     */
    public function getCache()
    {
        if (null === $this->cache) {
            $config = $this->getServiceLocator()->get('config')['rbac-conf'];
            $cache = $config['cache'];
            if (isset($cache['name']) && is_string($cache['name'])) {
                $this->cache = $this->getServiceLocator()->get($cache['name']);
            }
        }

        return $this->cache;
    }
}
