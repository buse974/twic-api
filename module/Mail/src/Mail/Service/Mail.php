<?php

namespace Mail\Service;

use Zend\Mail\Storage\Imap;
use Zend\Mail\Transport\Factory;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use Mail\Mime\Part;
use Zend\Mime\Mime;
use Mail\Template\Model\TplModel;

class Mail implements ServiceManagerAwareInterface
{
    /**
     * @var ServiceManager
     */
    protected $servicemanager;
    protected $tpl_storage;
    protected $storage;
    protected $transport;
    protected $is_init = false;

    public function init($login = null, $password = null)
    {
        $mail_conf = $this->servicemanager->get('config')['mail-conf'];

        $fct_storage   = array_merge_recursive($mail_conf['storage'], array('user' => $login, 'password' => $password));
        $fct_transport = array_merge_recursive($mail_conf['transport'], array('options' => array('connection_config' => array('username' => $login, 'password' => $password))));

        if ($fct_storage['active'] === true) {
            $this->storage = new Imap($fct_storage);
        }

        if ($fct_transport['active'] === true) {
            $this->transport = Factory::create($fct_transport);
        }

        $this->is_init = true;
    }

    /**
     * @throws \Exception
     *
     * @return \Zend\Mail\Storage\Imap
     */
    public function getStorage()
    {
        if (!$this->is_init) {
            $this->init();
        }

        return $this->storage;
    }

    /**
     * @throws \Exception
     *
     * @return \Zend\Mail\Transport\Smtp
     */
    public function getTransport()
    {
        if (!$this->is_init) {
            $this->init();
        }

        return $this->transport;
    }

    /**
     * Create Template mail.
     *
     * @invokable
     *
     * @param string $name
     * @param string $from
     * @param string $subject
     * @param string $content
     * @param array  $files
     *
     * @return bool
     */
    public function addTpl($name, $from, $subject, $content, array $files = array())
    {
        $m_tpl = new TplModel();
        $m_tpl->setName($name)
              ->setSubject($subject)
              ->setFrom($from);

        $html = new Part($content);
        $html->setEncoding(Mime::ENCODING_8BIT);
        $html->setType(Mime::TYPE_HTML);
        $html->setIsMappable(true);
        $m_tpl->append($html);

        foreach ($files as $file) {
            $attachement = new Part($file['content']);
            $attachement->setIsPath(true);
            $attachement->setIsEncoded($file['is_encoding']);
            $attachement->setEncoding((isset($file['encoding']) ? $file['encoding'] : Mime::ENCODING_BASE64));
            $attachement->setType($file['type']);
            $attachement->setFilename($file['name']);
            $attachement->setDisposition(Mime::DISPOSITION_ATTACHMENT);
            $attachement->saveBuffer();
            $m_tpl->append($attachement);
        }

        return ($this->getTplStorage()->write($m_tpl)) ? true : false;
    }

    public function sendTpl($name, $to, $datas = array())
    {
        $message =  $this->getMessage()->setBodyTpl($name, $datas)
                                       ->setTo($to);

        $this->getTransport()->send($message);

        return true;
    }

    /**
     * @return \Mail\Template\Storage\AbstractStorage
     */
    protected function getTplStorage()
    {
        if (null === $this->tpl_storage) {
            return $this->servicemanager->get($this->servicemanager->get('config')['mail-conf']['template']['storage']);
        }
    }

    /**
     * @return \Mail\Mail\Message
     */
    protected function getMessage()
    {
        return $this->servicemanager->get('Mail\Mail\Message')->setEncoding('UTF-8');
    }

    /**
     * @invokable
     */
    public function getListTpl()
    {
        $results = $this->getTplStorage()->getList();

        return array('count' => count($results), 'results' => $results);
    }

    /**
     * @invokable
     */
    public function getTpl($name)
    {
        if (!$this->getTplStorage()->exist($name)) {
            throw new \Exception('Model name does not exist');
        }

        return $this->getTplStorage()->read($name);
    }

    /**
     * Set service manager.
     *
     * @param ServiceManager $serviceManager
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->servicemanager = $serviceManager;

        return $this;
    }
}
