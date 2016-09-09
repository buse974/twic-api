<?php

namespace Mail;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Mail\Service\Mail;

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
        return array(
            'aliases' => [
                'mail.service' => Mail\Service\Mail::class,
            ],
            'factories' => [
                Mail\Service\Mail::class => function($container) {
                    $conf_mail = $container->get('config')['mail-conf'];
                    
                    $class_storage = $conf_mail['template']['storage'];
                    $bj_storage = null;
                    if(class_exists($class_storage)) {
                        $bj_storage = new $class_storage;
                        $bj_storage->setPath($conf_mail['template']['path']);
                    } elseif($container->has($class_storage)) {
                        $bj_storage = $container->get($class_storage);
                        $bj_storage->setPath($conf_mail['template']['path']);
                    }
                    
                    $mail =  new Mail();
                    $mail->setTplStorage($bj_storage)
                        ->setOptions($conf_mail);

                    return $mail;
                },
            ],
            
        );
    }
}
