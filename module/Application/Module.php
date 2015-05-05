<?php

/**
 * Zend Framework (http://framework.zend.com/).
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 *
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use JRpc\Json\Server\Exception\JrpcException;

class Module
{
    public function onBootstrap(MvcEvent $event)
    {
        $eventManager = $event->getApplication()->getEventManager();
        $eventManagerShare = $eventManager->getSharedManager();

        $eventManagerShare->attach('JRpc\Json\Server\Server', 'sendRequest.pre', function ($e) use ($event) {
            $authService = $event->getApplication()->getServiceManager()->get('auth.service');
            $premission = $e->getParams()['methode'];
            if ($premission !== 'user.login') {
                if (!$authService->hasIdentity()) {
                    throw new JrpcException('Not connected', -32027);
                }
                $rbacService = $event->getApplication()->getServiceManager()->get('rbac.service');
                $identity = $event->getApplication()->getServiceManager()->get('app_service_user')->getCacheIdentity();

                if (!$rbacService->isGranted($identity['roles'], $premission)) {
                    if ($e->getTarget()->getServiceMap()->getService($premission) === false) {
                        throw new JrpcException('Methode not fond', -32028);
                    }
                    throw new JrpcException('Not authorization', -32029);
                }
            }
        });

        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__.'/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__.'/src/'.__NAMESPACE__,
                ),
            ),
        );
    }
}
