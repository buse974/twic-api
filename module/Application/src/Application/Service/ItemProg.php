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

        if ($this->getMapper()->insert($m_item_prog)) {
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

        return $this->getServiceItemProgUserRelation()->add($user, $item_prog);
    }

    /**
     * @return \Application\Service\ItemProgUserRelation
     */
    public function getServiceItemProgUserRelation()
    {
        return $this->getServiceLocator()->get('app_service_item_user_relation');
    }
}
