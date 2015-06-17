<?php

// Composer autoloading
/*if (file_exists('vendor/autoload.php')) {
    $loader = include 'vendor/autoload.php';
    $loader->add('Zend', '/vendor/zendframework/zendframework/library/');
    $loader->add('ZendXml', '/vendor/zendframework/zendframework/library/');
}*/

// withi optimise
/*if (file_exists('vendor/autoload.php')) {
    $loader = include 'vendor/autoload.php';
}*/

/*include __DIR__ . '/vendor/zendframework/zendframework/library/Zend/Loader/AutoloaderFactory.php';
Zend\Loader\AutoloaderFactory::factory(array(
    'Zend\Loader\StandardAutoloader' => array(
        'autoregister_zf' => true,
        'namespaces' => array(
            'OpenTok' => __DIR__ . '/vendor/opentok/opentok/src/OpenTok',
            'Guzzle' => __DIR__ . '/vendor/guzzle/guzzle/src/Guzzle',
            'Symfony' => __DIR__ . '/vendor/symfony/event-dispatcher/Symfony',
        )
    )
));*/


include __DIR__ . '/vendor/zendframework/zendframework/library/Zend/Loader/AutoloaderFactory.php';
include __DIR__ . '/vendor/zendframework/zendframework/library/Zend/Loader/ClassMapAutoloader.php';
Zend\Loader\AutoloaderFactory::factory(array(
    'Zend\Loader\ClassMapAutoloader' => array(__DIR__ . '/autoload_classmap.php'),
    'Zend\Loader\StandardAutoloader' => array(
        'namespaces' => array(
            'OpenTok' => __DIR__ . '/vendor/opentok/opentok/src/OpenTok',
            'Guzzle' => __DIR__ . '/vendor/guzzle/guzzle/src/Guzzle',
            'Symfony' => __DIR__ . '/vendor/symfony/event-dispatcher/Symfony',
    )
)
));


if (!class_exists('Zend\Loader\AutoloaderFactory')) {
    throw new RuntimeException('Unable to load ZF2. Run `php composer.phar install` or define a ZF2_PATH environment variable.');
}
