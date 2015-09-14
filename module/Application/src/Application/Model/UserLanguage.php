<?php

namespace Application\Model;

use Application\Model\Base\UserLanguage as BaseUserLanguage;

class UserLanguage extends BaseUserLanguage
{
    protected $language;
    protected $level;

    public function setLanguage($language)
    {
        $this->language = $language;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function setLevel($level)
    {
        $this->level = $level;
    }

    public function getLevel()
    {
        return $this->level;
    }
}
