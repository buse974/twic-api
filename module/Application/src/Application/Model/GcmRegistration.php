<?php

namespace Application\Model;

use Application\Model\Base\GcmRegistration as BaseGcmRegistration;

class GcmRegistration extends BaseGcmRegistration
{
    protected $gcm_group;
    
    public function exchangeArray(array &$data)
    {
        parent::exchangeArray($data);
    
        $this->gcm_group = $this->requireModel('app_model_gcm_group', $data);
    }
    
    public function getGcmGroup()
    {
        return $this->gcm_group;
    }
    
    public function setGcmGroup($gcm_group)
    {
        $this->gcm_group = $gcm_group;
    
        return $this;
    }
}
