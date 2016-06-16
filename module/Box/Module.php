<?php

namespace Box;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Box\Service\Api;
use Zend\Http\Client;

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
                'box.service' => '\Box\Service\Api',
            ),
            'factories' => array(
                '\Box\Service\Api' => function ($sm) {
                    $box = $sm->get('config')['box-conf'];
                    $client = new Client();
                    $client->setOptions($sm->get('config')[$box['adapter']]);

                    return new Api($client, $box['apikey'], $box['url']);
                },
            ),
        );
    }
}
