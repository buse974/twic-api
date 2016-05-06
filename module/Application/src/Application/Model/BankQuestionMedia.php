<?php

namespace Application\Model;

use Application\Model\Base\BankQuestionMedia as BaseBankQuestionMedia;

class BankQuestionMedia extends BaseBankQuestionMedia
{
    protected $library;
      
    public function exchangeArray(array &$data)
    {
        parent::exchangeArray($data);
    
        $this->library = $this->requireModel('app_model_library', $data);
    }
    
    public function getLibrary() 
    {
        return $this->library;
    }
    
    public function setLibrary($library) 
    {
        $this->library = $library;
        
        return $this;
    }
}