<?php
namespace Application\Service;

use Dal\Service\AbstractService;
//use DateTime;
//use DateTimeZone;
//use Application\Model\Role as ModelRole;

class Country extends AbstractService
{
    /**
     * @invokable       
     *
     * @throws \Exception
     *
     * @return array
     */
    public function getList($filter=null)
    {
        $country = $this->getMapper();
        
        $res = $country->usePaginator($filter)->getList($filter);

        return array('list' => $res,
                    'count' => $country->count());
    }
}
