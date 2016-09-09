<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Cache User
 */
namespace Application\Service;

/**
 * Class CacheUser.
 */
class CacheUser 
{
    /**
     * Prefix.
     * 
     * @var string
     */
    protected $prefix = 'identity_';

    /**
     * Name key local.
     * 
     * @var string
     */
    protected $key_local = 'token';

    /**
     * Name key Global.
     * 
     * @var string
     */
    protected $key_global = 'id';

    /**
     * Save data Local user session.
     *
     * @param mixed $data
     *
     * @return bool
     */
    public function saveLocal($data)
    {
        $identity = $this->getServiceAuth()->getIdentity();

        $this->getCache()->setItem($this->prefix.$identity[$this->key_local], $data);
    }

    /**
     * Save data Global user.
     *
     * @param mixed $data
     *
     * @return bool
     */
    public function save($data)
    {
        $identity = $this->getServiceAuth()->getIdentity();

        $this->getCache()->setItem($this->prefix.$identity[$this->key_global], $data);
    }

    /**
     * Get data Local user.
     *
     * @return mixed
     */
    public function getLocal()
    {
        $identity = $this->getServiceAuth()->getIdentity();

        return ($this->getCache()->hasItem($this->prefix.$identity[$this->key_local])) ? $this->getCache()->getItem($this->prefix.$identity[$this->key_local]) : false;
    }

    /**
     * Get data Global user.
     *
     * @return mixed
     */
    public function get()
    {
        $identity = $this->getServiceAuth()->getIdentity();

        return ($this->getCache()->hasItem($this->prefix.$identity[$this->key_global])) ? $this->getCache()->getItem($this->prefix.$identity[$this->key_global]) : false;
    }

    /**
     * Get Storage if define.
     *
     * @return \Zend\Cache\Storage\StorageInterface
     */
    public function getCache()
    {
        $config = $this->container->get('config')['app-conf'];

        return $this->container->get($config['cache']);
    }

    /**
     * Get Service AuthenticationService.
     *
     * @return \Zend\Authentication\AuthenticationService
     */
    private function getServiceAuth()
    {
        return $this->container->get('auth.service');
    }
}
