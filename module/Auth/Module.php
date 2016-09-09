<?php

namespace Auth;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Auth\Authentication\Storage\CacheStorage;

class Module implements ConfigProviderInterface
{
    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__.'/src/'.__NAMESPACE__,
                ],
            ],
        ];
    }

    public function getConfig()
    {
        return include __DIR__.'/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return [
            'aliases' => [
                'auth.service' => 'Zend\Authentication\AuthenticationService',
                'token.storage' => 'Auth\Authentication\Storage\CacheStorage',
            ],
            'factories' => [
                'Zend\Authentication\AuthenticationService' => function ($container) {
                    $conf = $container->get('Config')['auth-conf'];

                    return new \Zend\Authentication\AuthenticationService(
                        $container->get($conf['storage']['name']), 
                        new Authentication\Adapter\DbAdapter(
                            $container->get($conf['adapter']['name']), 
                            $conf['adapter']['options']['table'], 
                            $conf['adapter']['options']['identity'], 
                            $conf['adapter']['options']['credential'], 
                            $conf['adapter']['options']['hash']
                        )
                    );
                },
                'Auth\Authentication\Storage\CacheStorage' => function ($container) {
                    $authconf = $container->get('Config')['auth-conf'];
                    $storage = new CacheStorage($container->get($authconf['storage']['options']['adpater']));
                    $storage->setRequest($container->get('Request'));
                    
                    return $storage;
                },
            ],
        ];
    }
}
