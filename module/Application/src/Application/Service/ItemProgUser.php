<?php
namespace Application\Service;

use Dal\Service\AbstractService;

class ItemProgUser extends AbstractService
{

    public function add($user, $item_prog)
    {
        $ret = array();
        
        foreach ($user as $u) {
            foreach ($item_prog as $ip) {
                $ret[$ip][$u] = $this->getMapper()->insertStudent($u, $ip);
            }
        }
        
        return $ret;
    }

    public function deleteByItemProg($item_prog)
    {
        $res_item_prog_user = $this->getMapper()->select($this->getModel()
            ->setItemProgId($item_prog));
        
        foreach ($res_item_prog_user as $m_item_prog_user) {
            $this->getServiceItemGrading()->deleteByItemProgUser($m_item_prog_user->getId());
        }
        
        return $this->getMapper()->delete($this->getModel()
            ->setItemProgId($item_prog));
    }

    public function get($item_prog_id, $user_id)
    {
        return $this->getMapper()->select($this->getModel()
            ->setItemProgId($item_prog_id)
            ->setUserId($user_id));
    }

    public function getById($id)
    {
        return $this->getMapper()->select($this->getModel()
            ->setId($id));
    }

    public function updateStartEndDate($item_prog, $started_date = null, $finished_date = null)
    {
        return $this->getMapper()->update($this->getModel()
            ->setStartedDate($started_date)
            ->setFinishedDate($finished_date), array('user_id' => $this->getServiceUser()
            ->getIdentity()['id'],'item_prog_id' => $item_prog));
    }
    
    /**
     * @invokable
     * 
     * @param integer $item_prog
     * @return integer
     */
    public function start($item_prog)
    {
        return $this->getMapper()->update($this->getModel()
            ->setStartedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'))
            , array('user_id' => $this->getServiceUser()
                ->getIdentity()['id'],'item_prog_id' => $item_prog));
    }
    
    /**
     * @invokable
     *
     * @param integer $item_prog
     * @return integer
     */
    public function end($item_prog)
    {
        return $this->getMapper()->update($this->getModel()
            ->setFinishedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'))
            , array('user_id' => $this->getServiceUser()
                ->getIdentity()['id'],'item_prog_id' => $item_prog));
    }

    /**
     * @invokable
     */
    public function getStartedConference()
    {
        return $this->getMapper()->getStartedConference($this->getServiceUser()
                ->getIdentity()['id']);
    }
    
    /**
     *
     * @param int $item_prog            
     * @param int $user            
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListByItemProg($item_prog, $user = null)
    {
        $m_item_prog_user = $this->getModel()->setItemProgId($item_prog);
        if ($user !== null) {
            $m_item_prog_user->setUserId($user);
        }
        
        return $this->getMapper()->select($m_item_prog_user);
    }

    /**
     *
     * @return \Application\Service\User
     */
    public function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }

    /**
     *
     * @return \Application\Service\ItemGrading
     */
    public function getServiceItemGrading()
    {
        return $this->getServiceLocator()->get('app_service_item_grading');
    }
}
