<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Circle Organization User Relation
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class OrganizationUser
 */
class OrganizationUser extends AbstractService
{

    /**
     * Add relation Organization user
     *
     * @param int $organization_id            
     * @param int $user_id            
     * @return int
     */
    public function add($organization_id, $user_id)
    {
        $ret = null;
        $res_organization_user = $this->getList($organization_id, $user_id);
        if ($res_organization_user->count() === 0) {
            $ret = $this->getMapper()->insert($this->getModel()
                ->setUserId($user_id)
                ->setOrganizationId($organization_id));
        }
        
        return $ret;
    }

    /**
     * Delete relation Organization user
     *
     * @param int $organization_id            
     * @param int $user_id            
     * @return int
     */
    public function remove($organization_id, $user_id)
    {
        $this->getMapper()->delete($this->getModel()
            ->setUserId($user_id)
            ->setOrganizationId($organization_id));
    }

    /**
     * Get List relation relation Organization User
     *
     * @param int $organization_id            
     * @param int $user_id            
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getList($organization_id = null, $user_id = null)
    {
        if (null === $organization_id && null === $user_id) {
            throw new \Exception('Error params');
        }
        
        return $this->getMapper()->select($this->getModel()
            ->setUserId($user_id)
            ->setOrganizationId($organization_id));
    }
}