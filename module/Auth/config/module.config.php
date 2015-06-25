<?php

return array(
    'auth-conf' => array(
        'adapter' => array(
            'name' => 'db-adapter',
            'options' => array(
                'table' => 'user',
                'identity' => 'email',
                'credential' => 'password',
                'hash' => 'MD5(?)',
            ),
        ),
        'storage' => array(
            'name' => 'token.storage',
            'options' => array(
                'adpater' => 'storage_memcached',
            ),
        ),
    ),
);
