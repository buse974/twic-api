<?php

namespace Mail\Template\Storage;

use Aws\S3\S3Client;

class FsS3Storage extends AbstractStorage
{
    protected $path;
    protected $init_path = false;

    public function write(\Mail\Template\Model\TplModel $model)
    {
        if (!is_dir($this->path)) {
            mkdir($this->path, 0777, true);
        }

        $fp = fopen($this->path.$model->getName().'.obj', 'w');
        $ret = fwrite($fp, serialize($model));
        fclose($fp);

        return ($ret) ? true : false;
    }

    public function read($name)
    {
        return unserialize(file_get_contents($this->path.$name.'.obj'));
    }

    public function getList()
    {
        $ret = array();

        if ($handle = opendir($this->path)) {
            while (false !== ($entry = readdir($handle))) {
                if (preg_match('/.obj$/', $entry)) {
                    $ret[] = unserialize(file_get_contents($this->path.$entry));
                }
            }
            closedir($handle);
        }

        return $ret;
    }

    public function exist($name)
    {
        return file_exists($this->path.$name.'.obj');
    }

    public function init($config = [])
    {
        if ($this->init_path === false) {
            $s3Client = new S3Client($config['options']);
            $s3Client->registerStreamWrapper();
            $init_path = true;
        }
        
        $this->path = sprintf('s3://%s/', $config['bucket']);
        
        return $this;
    }
}
