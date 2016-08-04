<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Guidelines View
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class GuidelinesView.
 */
class GuidelinesView extends AbstractService
{
    /**
     * Add State to a Guidelines.
     * 
     * @param string $state
     */
    public function add($state)
    {
        return $this->getMapper()->view($state, $this->getServiceUser()
            ->getIdentity()['id']);
    }

    /**
     * Check is state exist.
     * 
     * @param string $state
     *
     * @return bool
     */
    public function exist($state)
    {
        return ($this->getMapper()
            ->select($this->getModel()
            ->setUserId($this->getServiceUser()
            ->getIdentity()['id'])
            ->setState($state))
            ->count() > 0) ? true : false;
    }

    /**
     * Get Service User.
     * 
     * @return \Application\Service\User
     */
    private function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }
}
