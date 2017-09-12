<?php

namespace Mail\Mail;

use Zend\Mail\Message as BaseMessage;
use Zend\Mime\Message as MimeMessage;
use Mail\Template\Storage\AbstractStorage;

class Message extends BaseMessage
{
    protected $has_template = false;
    
    /**
     * Storage Tpl
     *
     * @var AbstractStorage
     */
    protected $tpl_storage;

    public function setBodyTpl($name, $datas)
    {
        if (null === $this->tpl_storage || !$this->tpl_storage->exist($name)) {
            throw new \Exception('Model name does not exist');
        }

        $tpl_model = $this->tpl_storage->read($name);

        $key = array();
        $value = array();
        foreach ($datas as $k => $v) {
            $key[] = sprintf('{%s}', $k);
            $value[] = $v;
        }

        $parts = [];
        $mimemessage = new MimeMessage();
        foreach ($tpl_model as $m_part) {
            if ($m_part->getIsMappable()) {
                $m_part->setDatas(array('k' => $key, 'v' => $value));
            }
            $parts[] = $m_part;
        }
        
        $mimemessage->setParts($parts);

        $this->setHeaders(["content-type" => "multipart/alternative"]);
        $this->setSubject(str_replace($key, $value, $tpl_model->getSubject()));
        $this->setFrom($tpl_model->getFrom(), $tpl_model->getFromName());
        $this->setBody($mimemessage);

        $this->has_template = true;

        return $this;
    }

    /**
     * Set Storage Mail
     *
     * @param \Mail\Template\Storage\AbstractStorage $storage
     */
    public function setTplStorage(AbstractStorage $storage)
    {
        $this->tpl_storage = $storage;

        return $this;
    }
}
