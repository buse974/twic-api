<?php

namespace Mail\Template\Storage;

class FsStorage extends AbstractStorage
{
    protected $path;

    public function write(\Mail\Template\Model\TplModel $model)
    {
        if (!is_dir($this->getPath())) {
            mkdir($this->getPath(), 0777, true);
        }

        $fp = fopen($this->getPath().$model->getName().'.obj', 'w+');
        $ret = fwrite($fp, serialize($model));
        fclose($fp);

        return ($ret) ? true : false;
    }

    public function read($name)
    {
        return unserialize(file_get_contents($this->getPath().$name.'.obj'));
    }

    public function getList()
    {
        $ret = array();

        if ($handle = opendir($this->getPath())) {
            while (false !== ($entry = readdir($handle))) {
                if (preg_match('/.obj$/', $entry)) {
                    $ret[] = unserialize(file_get_contents($this->getPath().$entry));
                }
            }
            closedir($handle);
        }

        return $ret;
    }

    public function exist($name)
    {
        return file_exists($this->getPath().$name.'.obj');
    }

    protected function getPath()
    {
        if (null === $this->path) {
            $this->path = $this->servicemanager->get('config')['mail-conf']['template']['path'];
        }

        return $this->path;
    }
}
