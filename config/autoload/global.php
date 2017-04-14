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

return [
    'version' => "v1.5b411",
    'build-commit' => 416,
    'app-conf' => [
        'cache' => 'storage_memcached',
        'secret_key' => 'toto',
    ],
    'dal-conf' => [
        'adapter' => 'db-adapter',
        'cache' => 'storage_memcached',
        'log' => 'log-system',
        'namespace' => [
            'app' => [
                'service' => 'Application\\Service',
                'mapper' => 'Application\\Mapper',
                'model' => 'Application\\Model',
            ],
        ],
    ],
    'box-conf' => [
        'apikey' => 'cxtjsc7gmibtu84caf0grun8thbp2ga1',
        'url' => 'https://view-api.box.com/1',
        'adapter' => 'http-adapter',
    ],
    'gcm' => [
        'api_key' => 'AIzaSyCOoniWUpLoLIQTaVWfMfv_hisjm6mXFUI',
        'sender_id' => '606201661282',
        'adapter' => 'http-adapter',
    ],
    'rbac-conf' => [
        'cache' => [
            'name' => 'storage_memcached',
            'enable' => true,
        ],
    ],
    'json-rpc-server' => [
        'cache' => 'storage_memcached',
        'log' => 'log-system',
        'environment' => 'dev', /* dev|prod */
        'persistence' => true,
        'services' => [
            'app_service_user',
            'app_service_role',
            'app_service_conversation',
            'app_service_conversation_user',
            'app_service_contact',
            'rbac_service_permission',
            'rbac_service_role_permission',
            'app_service_activity',
            'app_service_circle',
            'app_service_report',
            'app_service_resume',
            'app_service_message',
            'app_service_library',
            'app_service_language',
            'app_service_page',
            'app_service_page_relation',
            'app_service_page_user',
            'app_service_page_doc',
            'app_service_post',
            'app_service_post_doc',
            'app_service_video_archive',
            [
                'class' => 'addr_service_address',
                'methods' => [
                    'getAddress',
                ],
            ],
            [
                'class' => 'addr_service_country',
                'methods' => [
                    'getList',
                    'getCountry',
                ],
             ],
            [
                'class' => 'addr_service_city',
                'methods' => [
                    'getList',
                    'getCity',
                ],
            ],
            [
                'class' => 'addr_service_division',
                'methods' => [
                    'getList',
                    'getDivision',
                ],
            ],
        ],
        'headers' => [
           /*'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Allow-Headers' => 'Origin, X-Requested-With, Content-Type, Accept, Authorization',*/
        ],
    ],
    'zopentok-conf' => [
        'expire_time' => 60 * 60 * 24 * 30,
        'adapter' => 'http-adapter',
    ],
    'caches' => [
        'storage_memcached' => [
            'adapter' => [
                'name' => 'memcached',
                'options' => [
                    'namespace' => 'LMS416',
                    'liboptions' => [
                        ['option' => Memcached::OPT_PREFIX_KEY, 'value' => 'LMS416'],
                        ['option' => Memcached::OPT_LIBKETAMA_COMPATIBLE, 'value' => true],
                        ['option' => Memcached::OPT_SERIALIZER, 'value' => Memcached::SERIALIZER_IGBINARY],
                        ['option' => Memcached::OPT_DISTRIBUTION, 'value' => Memcached::DISTRIBUTION_CONSISTENT],
                    ],
                ],
            ],
            'plugins' => [
                'exception_handler' => ['throw_exceptions' => true],
            ],
        ],
    ],
];
