<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Group
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class Group.
 */
class Group extends AbstractService
{
    /**
     * Add Group.
     *
     * @invokable
     *
     * @param string    $set
     * @param string    $name
     * @param string    $uid
     * @param array|int $users
     *
     * @return int
     */
    public function add($set, $name, $uid = null, $users = null)
    {
        $m_group = $this->getModel()
            ->setUId($uid)
            ->setName($name);

        if ($this->getMapper()->insert($m_group) <= 0) {
            new \Exception('Error insert group');
        }

        $group_id = $this->getMapper()->getLastInsertValue();

        if (null !== $users) {
            $this->addUser($group_id, $users);
        }

        $this->getServiceSetGroup()->add($set, $group_id);

        return $group_id;
    }

    /**
     * Delete Group.
     *
     * @invokable
     *
     * @param int $id
     *
     * @return int
     */
    public function delete($id)
    {
        return $this->getMapper()->delete(
            $this->getModel()
                ->setId($id)
        );
    }

    /**
     * Update Group.
     *
     * @invokable
     *
     * @param int    $id
     * @param string $name
     *
     * @return int
     */
    public function update($id, $name)
    {
        return $this->getMapper()->update(
            $this->getModel()
                ->setId($id)
                ->setName($name)
        );
    }

    /**
     * Add User to group.
     *
     * @invokable
     *
     * @param integr       $id
     * @param array|integr $users
     *
     * @return array
     */
    public function addUser($id, $users)
    {
        if (!is_array($users)) {
            $users = [$users];
        }

        $ret = [];
        foreach ($users as $user) {
            $ret[$user] = $this->getServiceGroupUser()->add($id, $user);
        }

        return $ret;
    }

    /**
     * Delete And Add users to group.
     *
     * @invokable
     *
     * @param int       $id
     * @param int|array $users
     *
     * @return array
     */
    public function replaceUser($id, $users)
    {
        $ret = [];
        $this->getServiceGroupUser()->delete($id);

        if (!is_array($users)) {
            $users = [$users];
        }

        foreach ($users as $user) {
            $ret[$user] = $this->getServiceGroupUser()->add($id, $user);
        }

        return $ret;
    }

    /**
     * Get List Group.
     *
     * @invokable
     *
     * @param int    $course
     * @param int    $set
     * @param string $name
     * @param array  $filter
     *
     * @return \Dal\Db\ResultSet\ResultSet|array
     */
    public function getList($course, $set = null, $name = null, $filter = null)
    {
        $mapper = $this->getMapper();
        $res_group = $mapper->usePaginator($filter)->getList($set, $name, $course);

        foreach ($res_group as $m_group) {
            $m_group->setUsers(
                $this->getServiceGroupUser()
                    ->getListUser($m_group->getId())
            );
        }

        return ($filter === null) ? $res_group : ['count' => $mapper->count(), 'list' => $res_group];
    }

    /**
     * Delete.
     *
     * @invokable
     *
     * @param integr       $id
     * @param array|integr $user
     */
    public function deleteUser($id, $user = null)
    {
        return $this->getServiceGroupUser()->delete($id, $user);
    }

    /**
     * Get Service GroupUser.
     *
     * @return \Application\Service\GroupUser
     */
    private function getServiceGroupUser()
    {
        return $this->container->get('app_service_group_user');
    }

    /**
     * Get Service SetGroup.
     *
     * @return \Application\Service\SetGroup
     */
    private function getServiceSetGroup()
    {
        return $this->container->get('app_service_set_group');
    }
}
