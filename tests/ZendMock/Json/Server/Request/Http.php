<?php

/**
 * Request mock
 */
namespace Zend\Json\Server\Request;

use Zend\Json\Server\Request as JsonRequest;

class Http extends JsonRequest
{
    protected $rawJson;

    public function __construct()
    {
        $json = file_get_contents(__DIR__ . '/../../../../_files/input.data');

        $this->rawJson = $json;
        if (!empty($json)) {
            $this->loadJson($json);
        }
    }

    public function getRawJson()
    {
        return $this->rawJson;
    }
}
