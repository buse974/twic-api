<?php

namespace Application\Model;

use Application\Model\Base\Question as BaseQuestion;

class Question extends BaseQuestion
{
    protected $component;
    
    public function exchangeArray(array &$data)
    {
        parent::exchangeArray($data);
    
        $this->component = $this->requireModel('app_model_component', $data);
    }
    
    public function getComponent()
    {
        return $this->component;
    }
    
    public function setComponent($component)
    {
        $this->component = $component;
    
        return $this;
    }
}
