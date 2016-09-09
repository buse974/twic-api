<?php

namespace Rbac;

use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{

    public function getConfig()
    {
        return include __DIR__.'/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'aliases' => array(
                'rbac.service' => 'Rbac\Service\Rbac',
            ),
            'factories' => array(
                'Rbac\Service\Rbac' => function ($sm) {
                    // return new \Rbac\Service\Rbac($sm->get('Config')['rbac_base']);
                    return new \Rbac\Service\Rbac();
                },
            ),
        );
    }
}
