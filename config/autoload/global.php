<?php

/**
 * Global Configuration Override.
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return array(
    'app-conf' => array(
        'cache'   => 'storage_memcached',
    ),
    'dal-conf' => array(
        'adapter' => 'db-adapter',
        'cache'   => 'storage_memcached',
        'log'     => 'log-system',
        'namespace' => array(
            'app' => array(
                'service' => 'Application\\Service',
                'mapper'  => 'Application\\Mapper',
                'model'   => 'Application\\Model',
            ),
        ),
    ),
    'rbac-conf' => array(
        'cache' => array(
            'name' => 'storage_memcached',
            'enable' => true,
        ),
    ),
    'json-rpc-server' => array(
        'cache' => 'storage_memcached',
        'log' => 'log-system',
        'persistence' => true,
        'services' => array(
            'app_service_user',
            'app_service_item',
            'app_service_school',
            'app_service_program',
            'app_service_role',
            'app_service_videoconf',
            'app_service_course',
            'app_service_grading',
            'app_service_grading_policy',
            'app_service_videoconf_invitation',
            'mail.service',
            'app_service_material_document',
            'app_service_faq',
            'app_service_thread',
            'app_service_thread_message',
            'app_service_message',
            'app_service_module',
            'app_service_module_assignments',
            'app_service_item_assignment',
            'app_service_item_prog',
            'app_service_grading_policy_grade',
        ),
        'headers' => array(
           /* 'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Allow-Headers' => 'Origin, X-Requested-With, Content-Type, Accept, Authorization',*/
        ),
    ),
    'mail-conf' => array(
        'template' => array(
            'storage' => 'Mail\Template\Storage\FsStorage',
            'path' => __DIR__.'/../../../tpl/',
        ),
        'storage' => array(
            'active' => false,
        ),
        'transport' => array(
            'active' => true,
            'type'   => 'sendmail'/*'smtp'*/,
            'options' => array(
                /*'name'              => 'christophe',
    			'host'              => 'smtp.thestudnet.com',
    			'port'              => 587,
    			'connection_class'  => 'plain',
    			'connection_config' => array(
    				'ssl' => 'tls',
    			),*/
            ),
        ),
    ),
    'zopentok-conf' => array(
        'api_key'     =>  '45105812',
        'api_secret'  =>  '071024b92d648e39339d0bb891668401a2254bd4',
        'expire_time' => 60 * 60 * 24 * 30,
        'adapter'     => 'http-adapter',
    ),
    'dms-conf' => array(
            'size_allowed' => array(
                    array('width' => 300, 'height' => 200),
                    array('width' => 300, 'height' => 300),
                    array('width' => 150, 'height' => 100),
                    array('width' => 80, 'height' => 80),
                    array('width' => 300),
            ),
            'default_path' => 'upload/',
            'adapter' => 'http-adapter',
            'convert' => array(
                    'tmp' => '/tmp/',
            ),
            'headers' => array(
                    'Access-Control-Allow-Origin' => 'http://local.wow.in',
                    'Access-Control-Allow-Credentials' => 'true',
            ),
    ),
);
