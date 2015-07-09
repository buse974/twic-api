<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class ItemProgUser extends AbstractService
{
    public function add($user, $item_prog)
    {
        $ret = array();
        $m_item_prog_user = $this->getModel();

        foreach ($user as $u) {
            foreach ($item_prog as $ip) {
                $m_item_prog_user->setUserId($u)->setItemProgId($ip);
                $ret[$ip][$u] = $this->getMapper()->insert($m_item_prog_user);
            }
        }

        return $ret;
    }

    public function deleteByItemProg($item_prog)
    {
        $res_item_prog_user = $this->getMapper()->select($this->getModel()->setItemProgId($item_prog));

        foreach ($res_item_prog_user as $m_item_prog_user) {
            $this->getServiceItemGrading()->deleteByItemProgUser($m_item_prog_user->getId());
        }

        return $this->getMapper()->delete($this->getModel()->setItemProgId($item_prog));
    }

    public function get($item_prog_id, $user_id)
    {
        return $this->getMapper()->select($this->getModel()->setItemProgId($item_prog_id)->setUserId($user_id));
    }
    
    public function getById($id)
    {
        return $this->getMapper()->select($this->getModel()->setId($id));
    }

    /**
     * @param int $item_prog
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListByItemProg($item_prog)
    {
        return $this->getMapper()->select($this->getModel()->setItemProgId($item_prog));
    }

    /**
     * @return \Application\Service\ItemGrading
     */
    public function getServiceItemGrading()
    {
        return $this->getServiceLocator()->get('app_service_item_grading');
    }
}
