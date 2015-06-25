<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class GradingPolicyGrade extends AbstractService
{
    /**
     * @invokable
     *
     * @param array  $avg
     * @param array  $filter
     * @param string $search
     */
    public function getList($avg = array(), $filter = array(), $search = null)
    {
        $mapper = $this->getMapper();
        $res_gradingpolicygrade = $mapper->usePaginator($filter)->getList($avg, $filter, $search);

        return array('count' => $mapper->count(),'list' => $res_gradingpolicygrade);
    }
}
