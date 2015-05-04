<?php

namespace Mail\Mail;

use Zend\Mail\Message as BaseMessage;
use Zend\Mime\Message as MimeMessage;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;

class Message extends BaseMessage implements ServiceManagerAwareInterface
{
    protected $has_template = false;
    protected $tpl_storage;

    public function setBodyTpl($name, $datas)
    {
        if (!$this->getTplStorage()->exist($name)) {
            throw new \Exception('Model name does not exist');
        }

        $tpl_model = $this->getTplStorage()->read($name);

        $key = array();
        $value = array();
        foreach ($datas as $k => $v) {
            $key[] = sprintf('{%s}', $k);
            $value[] = $v;
        }

        $mimemessage = new MimeMessage();
        foreach ($tpl_model as $m_part) {
            if ($m_part->getIsMappable()) {
                $m_part->setDatas(array('k' => $key, 'v' => $value));
            }
            $mimemessage->addPart($m_part);
        }

        $this->setSubject(str_replace($key, $value, $tpl_model->getSubject()));
        $this->setFrom($tpl_model->getFrom());
        $this->setBody($mimemessage);

        $this->has_template = true;

        return $this;
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
