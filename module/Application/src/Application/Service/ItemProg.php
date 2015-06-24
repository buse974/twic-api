<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class ItemProg extends AbstractService
{
    /**
     * Create Session Programmation.
     *
     * @invokable
     *
     * @param int    $item
     * @param string $start_date
     *
     * @throws \Exception
     *
     * @return int
     */
    public function add($item, $start_date)
    {
        $m_item_prog = $this->getModel()->setItemId($item)->setStartDate($start_date);

        if ($this->getMapper()->insert($m_item_prog) <= 0) {
            print_r($ret);
            throw new \Exception('error insert item prog');
        }

        return $this->getMapper()->getLastInsertValue();
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
}
