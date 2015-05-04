<?php
namespace Rbac;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Dal\Db\ResultSet\ResultSet;
use Dal\Db\TableGateway\TableGateway;
use Dal\Db\Sql\Sql;
use Rbac\Mapper\RbacRole;

class Module implements ConfigProviderInterface
{

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            ),
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php'
            )
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'aliases' => array(
                'rbac.service' => 'Rbac\Service\Rbac'
            ),
            'factories' => array(
                'Rbac\Service\Rbac' => function ($sm) {
                    // return new \Rbac\Service\Rbac($sm->get('Config')['rbac_base']);
                    return new \Rbac\Service\Rbac();
                }
            )
        );
    }
}
