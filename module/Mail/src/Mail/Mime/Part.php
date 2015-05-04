<?php

namespace Mail\Mime;

use Zend\Mime\Part as BasePart;
use Zend\Mime\Mime;

class Part extends BasePart implements \Serializable, \JsonSerializable
{
    protected $is_encoded = false;
    protected $is_mappable = false;
    protected $is_path = false;
    protected $datas = null;

    public function setIsPath($is_path)
    {
        $this->is_path = $is_path;

        return $this;
    }

    public function getIsPath()
    {
        return $this->is_path;
    }

    public function setIsMappable($is_mappable)
    {
        $this->is_mappable = $is_mappable;

        return $this;
    }

    public function getIsMappable()
    {
        return $this->is_mappable;
    }

    public function setDatas($datas)
    {
        $this->datas = $datas;

        return $this;
    }

    public function getDatas()
    {
        return $this->datas;
    }

    public function setIsEncoded($is_encoded)
    {
        $this->is_encoded = $is_encoded;

        return $this;
    }

    /**
     * Get the Content of the current Mime Part in the given encoding.
     *
     * @param string $EOL
     *
     * @return string
     */
    public function getContent($EOL = Mime::LINEEND)
    {
        if ($this->is_encoded) {
            $content = $this->content;
        } else {
            if ($this->is_path) {
                $this->content = fopen($this->content, 'r');
                $this->isStream = true;
            }
            $content = parent::getContent($EOL);
        }
        if ($this->is_mappable && $this->datas !== null) {
            $content = str_replace($this->datas['k'], $this->datas['v'], $content);
        }

        return  $content;
    }

    public function saveBuffer()
    {
        $this->content = $this->getContent();
        $this->is_encoded = true;
        $this->isStream = false;
        $this->is_path = false;

        return $this;
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setEncoding($encoding)
    {
        $this->encoding = $encoding;

        return $this;
    }

    public function getEncoding()
    {
        return $this->encoding;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setDisposition($disposition)
    {
        $this->disposition = $disposition;

        return $this;
    }

    public function getDisposition()
    {
        return $this->disposition;
    }

    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setCharset($charset)
    {
        $this->charset = $charset;

        return $this;
    }

    public function getCharset()
    {
        return $this->charset;
    }

    public function setBoundary($boundary)
    {
        $this->boundary = $boundary;

        return $this;
    }

    public function getBoundary()
    {
        return $this->boundary;
    }

    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function serialize()
    {
        return serialize(get_object_vars($this));
    }

    /**
     * @param serialized
     */
    public function unserialize($serialized)
    {
        foreach (unserialize($serialized) as $key => $value) {
            $this->$key = $value;
        }
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
