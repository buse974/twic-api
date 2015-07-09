<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class Research extends AbstractService
{
    /**
     * @invokable
     *
     * @param string $filter              
     *
     * @return array
     */
    public function getList($filter = null)
    {
        $mapper = $this->getMapper();
                
        $res = $res->toArray();        
       
        return array('list' => $res,'count' => $mapper->count());
    }    
}
