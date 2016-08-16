<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Research
 */
namespace Application\Service;

use Dal\Service\AbstractService;
use Application\Model\Role as ModelRole;

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
        $identity = $this->getServiceUser()->getIdentity();
        $is_sadmin_admin = (in_array(ModelRole::ROLE_SADMIN_STR, $identity['roles']) || in_array(ModelRole::ROLE_ADMIN_STR, $identity['roles']));
        
        
        $mapper = $this->getMapper();
        $res = $mapper->usePaginator($filter)->getList($string);

        return ['list' => $res, 'count' => $mapper->count()];
    }
    
    /**
     * Get Service Grading
     *
     * @return \Application\Service\User
     */
    private function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }
}
