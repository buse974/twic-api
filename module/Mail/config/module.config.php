<?php

return array(
    'mail-conf' => array(
        'template' => array(
            'storage' => 'Mail\Template\Storage\FsStorage',
            'path' => __DIR__.'/../../../tpl/',
        ),
        'storage' => array(
            'active' => true,
            'host' => 'imap.quicopro.ti1.fr',
               'ssl'  => 'tls', ),
        'transport' => array(
            'active' => true,
            'type'   => 'smtp',
            'options' => array(
                'name'              => 'christophe',
                'host'              => '',
                'port'              => 587,
                'connection_class'  => 'plain',
                'connection_config' => array(
                    'ssl' => 'tls',
                ),
            ),
        ),
    ),
);
