<?php

namespace LinkedIn\Service;

use Zend\Http\Request;
use LinkedIn\Service\AbstractApi;
use LinkedIn\Model\AccessToken;

class Api extends AbstractApi
{
    public function accessToken($code)
    {
        $this->setMethode(Request::METHOD_POST);
        $this->setPath(sprintf('oauth/v2/accessToken'));
        $this->setPost([
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $this->redirect_uri,
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
        ]);

        return new AccessToken($this->getBody($this->send()));
    }
}
