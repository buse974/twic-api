<?php

return array(
    'mail-conf' => array(
        /*'template' => array(
            'storage' => [
                'name' => 'fs',
                'path' => __DIR__.'/../../../tpl/',
            ],
            'storage' => [
                'name' => 's3',
                'bucket' => 'prod-stdn-static',
                'options' => [
                    'version' => 'latest',
                    'region' => 'us-east-1',
                    'credentials' => [
                        'key' => 'AKIAI5A2CCVNKTBXLWKA',
                        'secret' => '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',
                    ]
            ]
        ),*/
        'storage' => array(
            'active' => true,
            'host' => 'imap.quicopro.ti1.fr',
               'ssl' => 'tls', ),
        'transport' => array(
            'active' => true,
            'type' => 'smtp',
            'options' => array(
                'name' => 'christophe',
                'host' => '',
                'port' => 587,
                'connection_class' => 'plain',
                'connection_config' => array(
                    'ssl' => 'tls',
                ),
            ),
        ),
    ),
);
