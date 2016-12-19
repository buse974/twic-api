<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Opentok
 */
namespace Application\Service;

use OpenTok\MediaMode;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class Opentok.
 */
class Opentok implements ServiceLocatorAwareInterface
{

    /**
     * Service Locator.
     *
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * Create Session.
     *
     * @invokable
     *
     * @param string $media_mode
     *
     * @return string
     */
    public function createSession($media_mode = MediaMode ::ROUTED)
    {
        return $this->getServiceOpenTok()->createSession($media_mode);
    }

    /**
     * Get Service OpenTok.
     *
     * @return \ZOpenTok\Service\OpenTok
     */
    protected function getServiceOpenTok()
    {
        return $this->container->get('opentok.service');
    }

    /**
     * Set service locator.
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        
        return $this;
    }
}
