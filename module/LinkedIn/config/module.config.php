<?php

return [
    'http-adapter' => [
        'adapter' => 'Zend\Http\Client\Adapter\Curl',
        'maxredirects' => 5,
        'sslverifypeer' => false,
        'ssltransport' => 'tls',
        'timeout' => 10,
    ],
    'linkedin-conf' => [
        'api_url' => 'https://api.linkedin.com/v1',
        'client_id' => '77gpz90fnfvx72',
        'client_secret' => '8ENuBYC0JujwDrG1',
        'redirect_uri' => 'https://v2.thestudnet.com/linkedin_signin',
        'adapter' => 'http-adapter'
    ],
];
