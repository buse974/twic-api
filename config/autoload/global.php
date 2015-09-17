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
        'cache' => 'storage_memcached',
        'secret_key' => 'toto',
        'secret_key_fb' => 'KR1inakD9ucyW7TPe9mPxUCYmlDi9VuzhjmUCnmd',
        'secret_key_fb_debug' => true,
    ),
    'dal-conf' => array(
        'adapter' => 'db-adapter',
        'cache' => 'storage_memcached',
        'log' => 'log-system',
        'namespace' => array(
            'app' => array(
                'service' => 'Application\\Service',
                'mapper' => 'Application\\Mapper',
                'model' => 'Application\\Model',
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
            'app_service_grading_policy_grade_comment',
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
            'app_service_address',
            'app_service_city',
            'app_service_division',
            'app_service_questionnaire',
            'app_service_answer',
            'app_service_item_prog_user',
	        'app_service_country',
            'app_service_component',
            'app_service_country',
            'app_service_user_language',
            'app_service_language',
            'app_service_language_level',
            'app_service_event',
            'app_service_dimension',
            'app_service_question',
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
            'type' => 'sendmail'/*'smtp'*/,
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
        'api_key' => '45105812',
        'api_secret' => '071024b92d648e39339d0bb891668401a2254bd4',
        'expire_time' => 60 * 60 * 24 * 30,
        'adapter' => 'http-adapter',
    ),
    'dms-conf' => array(
            'adapter' => 'http-adapter',
            'convert' => array(
                    'tmp' => '/tmp/',
            ),
            'headers' => array(
                    'Access-Control-Allow-Origin' => 'http://lms.com',
                    'Access-Control-Allow-Credentials' => 'true',
                    'Access-Control-Allow-Headers' => 'Origin, X-Requested-With, Content-Type, Accept, Authorization',
            ),
    ),
);
