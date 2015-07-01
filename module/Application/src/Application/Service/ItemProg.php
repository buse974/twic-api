<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class ItemProg extends AbstractService
{
    /**
     * @param int $item_assignment
     *
     * @throws \Exception
     *
     * @return \Application\Model\ItemProg
     */
    public function getByItemAssignment($item_assignment)
    {
        $res_item_prog = $this->getMapper()->getByItemAssignment($item_assignment);

        if ($res_item_prog->count() <= 0) {
            throw new \Exception('error select item by itemassignement');
        }

        return $res_item_prog->current();
    }

    /**
     * Create Session Programmation.
     *
     * @invokable
     *
     * @param int       $item
     * @param string    $start_date
     * @param int|array $users
     *
     * @throws \Exception
     *
     * @return int
     */
    public function add($item, $start_date, $users = null)
    {
        $m_item_prog = $this->getModel()->setItemId($item)->setStartDate($start_date);
        if ($this->getMapper()->insert($m_item_prog) <= 0) {
            print_r($ret);
            throw new \Exception('error insert item prog');
        }
        $id = $this->getMapper()->getLastInsertValue();

        if ($users !== null) {
            $this->addUser($id, $users);
        }

        return $id;
    }

    /**
     * Update User.
     *
     * @invokable
     *
     * @param int    $id
     * @param string $start_date
     * @param array  $users
     *
     * @return int
     */
    public function update($id, $start_date = null, $users = null)
    {
        $m_item_prog = $this->getModel();

        $m_item_prog->setId($id)
            ->setStartDate($start_date);

        if ($users !== null) {
            $this->getServiceItemProgUser()->deleteByItemProg($id);
            $this->addUser($id, $users);
        }

        return $this->getMapper()->update($m_item_prog);
    }

    /**
     * @invokable
     *
     * @param int $id
     *
     * @return int
     */
    public function delete($id)
    {
        $this->getServiceItemProgUser()->deleteByItemProg($id);
        $this->getServiceItemAssignment()->deleteByItemProg($id);

        return $this->getMapper()->delete($this->getModel()->setId($id));
    }

    /**
     * add User in a programmation.
     *
     * @invokable
     *
     * @param int|array $item_prog
     * @param int|array $user
     */
    public function addUser($item_prog, $user)
    {
        if (!is_array($user)) {
            $user = array(
                    $user,
            );
        }

        if (!is_array($item_prog)) {
            $item_prog = array(
                    $item_prog,
            );
        }

        return $this->getServiceItemProgUser()->add($user, $item_prog);
    }

    /**
     * @invokable
     *
     * @param int $item
     * @param string $start
     * @param string $end
     *
     * @return array
     */
    public function getList($item = null, $start = null, $end = null)
    {   
 
        $res_item_progs = $this->getMapper()->getList($this->getServiceUser()->getIdentity() , $item, $start, $end);
        foreach ($res_item_progs as $m_item_prog) {
            $m_item_prog->setUsers($this->getServiceUser()->getListByItemProg($m_item_prog->getId()));
        }

        return $res_item_progs;
    }

    public function deleteByItem($item)
    {
        $res_item_prog = $this->getMapper()->select($this->getModel()->setItemId($item));

        foreach ($res_item_prog as $m_item_prog) {
            $this->getServiceItemProgUser()->deleteByItemProg($m_item_prog->getId());
            $this->getServiceItemAssignment()->deleteByItemProg($m_item_prog->getId());
        }

        $this->getMapper()->delete($this->getModel()->setItemId($item));
    }

    /**
     * @return \Application\Service\ItemProgUser
     */
    public function getServiceItemProgUser()
    {
        return $this->getServiceLocator()->get('app_service_item_prog_user');
    }

    /**
     * @return \Application\Service\ItemAssignment
     */
    public function getServiceItemAssignment()
    {
        return $this->getServiceLocator()->get('app_service_item_assignment');
    }

    /**
     * @return \Application\Service\User
     */
    public function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }
    
    /**
     * @return \Zend\Authentication\AuthenticationService
     */
    public function getServiceAuth()
    {
        return $this->getServiceLocator()->get('auth.service');
    }
}
