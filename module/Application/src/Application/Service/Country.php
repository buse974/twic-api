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
     * @param string $string 
     *
     * @return array
     */
    public function getList($string = null)
    {
        $country = $this->getMapper();
        
        $res = $country->getList($string);

        return array('list' => $res,
                    'count' => $country->count());
    }
}
