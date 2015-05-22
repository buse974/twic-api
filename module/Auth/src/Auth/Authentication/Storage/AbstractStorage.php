<?php

namespace Auth\Authentication\Storage;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;

abstract class AbstractStorage implements StorageInterface, ServiceManagerAwareInterface
{
    /**
     * @var string
     */
    protected $token;

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * Set service manager instance.
     *
     * @param ServiceManager $locator
     *
     * @return \Auth\Adapter\Storage\DbStorage
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }

    /**
     * Retrieve service manager instance.
     *
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    public function getToken()
    {
    	syslog(1, json_encode($this->getServiceManager()->get('Request')->getHeaders()->toArray()));
        if (null === $this->token && $aut = $this->getServiceManager()->get('Request')->getHeader('Authorization', null)) {
            $this->token = $aut->getFieldValue();
        }

        return $this->token;
    }
}
