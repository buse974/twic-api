<?php

namespace Auth;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Auth\Authentication\Storage\CacheStorage;

class Module implements ConfigProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__.'/src/'.__NAMESPACE__,
                ),
            ),
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__.'/autoload_classmap.php',
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__.'/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'aliases' => array(
                'auth.service' => 'Zend\Authentication\AuthenticationService',
                'token.storage' => 'Auth\Authentication\Storage\CacheStorage',
            ),
            'factories' => array(
                'Zend\Authentication\AuthenticationService' => function ($sm) {
                    $conf = $sm->get('Config')['auth-conf'];

                    return new \Zend\Authentication\AuthenticationService($sm->get($conf['storage']['name']), new Authentication\Adapter\DbAdapter($sm->get($conf['adapter']['name']), $conf['adapter']['options']['table'], $conf['adapter']['options']['identity'], $conf['adapter']['options']['credential'], $conf['adapter']['options']['hash']));
                },
                'Auth\Authentication\Storage\CacheStorage' => function ($sm) {
                    return new CacheStorage($sm->get($sm->get('Config')['auth-conf']['storage']['options']['adpater']));
                },
            ),
        );
    }
}
