<?php
namespace Application\Service;

use Dal\Service\AbstractService;

class Country extends AbstractService
{

    /**
     * @invokable
     *
     * @param string $string            
     *
     * @return array
     */
    public function getList($string = null)
    {
        $mapper = $this->getMapper();
        
        $res = $mapper->getList($string);
        
        return ['list' => $res,'count' => $mapper->count()];
    }
}
