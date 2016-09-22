<?php

return array(
    'auth-conf' => array(
        'adapter' => array(
            'name' => 'db-adapter',
            'options' => array(
                'table' => 'user',
                'identity' => 'email',
                'credential' => 'password',
                'lost' => 'new_password',
                'hash' => 'MD5(?)',
            ),
        ),
        'storage' => array(
            'name' => 'token.storage.bddmem',
            'options' => array(
                'adpater' => 'storage_memcached',
                'bdd_adpater' => 'db-adapter',
            ),
        ),
    ),
);
