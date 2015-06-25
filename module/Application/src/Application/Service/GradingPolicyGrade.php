<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class GradingPolicyGrade extends AbstractService
{
    /**
     * @invokable
     *
     * @param array $avg
     * @param array $filter
     */
    public function getList($avg = array(), $filter = array())
    {
    	$mapper = $this->getMapper();
        $res_gradingpolicygrade = $mapper->usePaginator($filter)->getList($avg, $filter);
        
        return array('count' => $mapper->count(),'list' =>  $res_gradingpolicygrade);
    }
}
