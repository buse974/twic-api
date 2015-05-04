<?php

namespace Auth\Authentication\Adapter\Model;

interface IdentityInterface extends \JsonSerializable
{
    public function getEmail();

    public function getLastname();

    public function getFirstname();

    public function getToken();

    public function getCreatedDate();

    public function getExpirationDate();

    public function exchangeArray(array $datas);
}
