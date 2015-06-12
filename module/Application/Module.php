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
use Rbac\Db\Model\Role;

class Module
{
    public function onBootstrap(MvcEvent $event)
    {
        $eventManager = $event->getApplication()->getEventManager();
        $eventManagerShare = $eventManager->getSharedManager();
        $eventManagerShare->attach('JRpc\Json\Server\Server', 'sendRequest.pre', function ($e) use ($event) {
            $permission = $e->getParams()['methode'];
            $authService = $event->getApplication()->getServiceManager()->get('auth.service');
            if ($authService->hasIdentity()) {
             $identity = $event->getApplication()->getServiceManager()->get('app_service_user')->getCacheIdentity();
            } else {
             $identity['roles'] = Role::STR_GUEST;
            }
            $rbacService = $event->getApplication()->getServiceManager()->get('rbac.service');
              
            if(!$rbacService->isGranted($identity['roles'], $permission)) {
                if ($e->getTarget()->getServiceMap()->getService($permission) === false) {
                    throw new JrpcException('Method not found', -32028);
                }
                if (!$authService->hasIdentity()) {
                	throw new JrpcException('No connected', -32027);
                }
                throw new JrpcException('No authorization', -32029);
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
