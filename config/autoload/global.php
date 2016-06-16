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
    'rbac-conf' => [
        'cache' => [
            'name' => 'storage_memcached',
            'enable' => true,
        ],
    ],
    'json-rpc-server' => [
        'cache' => 'storage_memcached',
        'log' => 'log-system',
        'persistence' => true,
        'services' => [
            'app_service_user',
            'app_service_item',
            'app_service_school',
            'app_service_program',
            'app_service_role',
            'app_service_videoconf',
            'app_service_course',
            'app_service_grading',
            'app_service_grading_policy',
            'mail.service',
            'app_service_material_document',
            'app_service_faq',
            'app_service_thread',
            'app_service_thread_message',
            'app_service_message',
            'app_service_module_assignments',
            'app_service_item_assignment',
            'app_service_item_assignment_comment',
            'app_service_task',
            'app_service_task_share',
            'app_service_mail',
            'app_service_conversation',
            'app_service_conversation_user',
            'app_service_contact',
            'app_service_research',
            'app_service_videoconf_doc',
            'app_service_research',
            'rbac_service_permission',
            'rbac_service_role_permission',
            'app_service_feed',
            'app_service_feed_comment',
            'app_service_like',
            'app_service_resume',
            'app_service_questionnaire',
            'app_service_answer',
            'app_service_component',
            'app_service_user_language',
            'app_service_language',
            'app_service_language_level',
            'app_service_event',
            'app_service_event_user',
            'app_service_event_comment',
            'app_service_dimension',
            'app_service_question',
            'app_service_activity',
            'app_service_scale',
            'app_service_dimension_scale',
            'app_service_component_scale',
            'app_service_guidelines',
            'app_service_connection',
            'app_service_poll',
            'app_service_poll_item',
            'app_service_document',
            'app_service_set',
            'app_service_library',
            'app_service_group',
            'app_service_opt_grading',
            'app_service_bank_question',
            'app_service_ct_date',
            'app_service_ct_done',
            'app_service_ct_rate',
            'app_service_ct_group',
            'app_service_submission',
            'app_service_submission_pg',
            'app_service_text_editor',
            'app_service_conversation_opt',
            'app_service_bank_question_tag',
            'app_service_sub_quiz',
            'app_service_criteria',
            'app_service_submission_user_criteria',
            'app_service_submission_comments',
            'app_service_submission_user',
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
           /* 'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Allow-Headers' => 'Origin, X-Requested-With, Content-Type, Accept, Authorization',*/
        ],
    ],
    'mail-conf' => [
        'template' => [
            'storage' => 'Mail\Template\Storage\FsStorage',
            'path' => __DIR__.'/../../../tpl/',
        ],
        'storage' => [
            'active' => false,
        ],
        'transport' => [
            'active' => true,
            'type' => 'sendmail'/*'smtp'*/,
            'options' => [
                /*'name'              => 'christophe',
    			'host'              => 'smtp.thestudnet.com',
    			'port'              => 587,
    			'connection_class'  => 'plain',
    			'connection_config' => [
    				'ssl' => 'tls',
    			],*/
            ],
        ],
    ],
    'zopentok-conf' => [
        'expire_time' => 60 * 60 * 24 * 30,
        'adapter' => 'http-adapter',
    ],
    'dms-conf' => [
            'adapter' => 'http-adapter',
            'convert' => [
                    'tmp' => '/tmp/',
            ],
            'headers' => [
                    'Access-Control-Allow-Origin' => 'http://lms.com',
                    'Access-Control-Allow-Credentials' => 'true',
                    'Access-Control-Allow-Headers' => 'Origin, X-Requested-With, Content-Type, Accept, Authorization',
            ],
    ],
];
