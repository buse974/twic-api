<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Research
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class Research.
 */
class Research extends AbstractService
{
    /**
     * Get List Research.
     * 
     * @invokable
     *
     * @param string $string
     * @param array  $filter
     *
     * @return array
     */
    public function getList($string, $filter = null)
    {
        $mapper = $this->getMapper();
        $res = $mapper->usePaginator($filter)->getList($string);

        return ['list' => $res, 'count' => $mapper->count()];
    }
}
