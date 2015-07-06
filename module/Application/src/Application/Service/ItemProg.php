<?php

namespace Application\Service;

use Dal\Service\AbstractService;
use Application\Model\Item;

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
            throw new \Exception('error insert item prog');
        }
        $id = $this->getMapper()->getLastInsertValue();

        $m_item = $this->getServiceItem()->get($item);
        switch ($m_item->getType()) {
        	case Item::TYPE_LIVE_CLASS :
        		$conversation = $this->getServiceConversationUser()->createConversation($users);
        		$videoconf = $this->getServiceVideoconf()->add('', '', $start_date, $id, $conversation);
        		$this->getServiceVideoconfConversation()->add($conversation, $videoconf);
        	break;
        	case Item::TYPE_WORKGROUP :
        		$conversation = $this->getServiceConversationUser()->createConversation($users);
        		$videoconf = $this->getServiceVideoconf()->add('', '', $start_date, $id, $conversation);
        		$this->getServiceVideoconfConversation()->add($conversation, $videoconf);
        	break;
        	
        	default:
        	break;
        }
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
        //$this->getServiceConversationUser()->deleteByItemProg($id);
        //$this->getServiceVideoconfConversation()->deleteByItemProg($id);

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
     *
     * @return array
     */
    public function getList($item)
    {
        $res_item_progs = $this->getMapper()->getList($item);
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
     * @return \Application\Service\Videoconf
     */
    public function getServiceVideoconf()
    {
    	return $this->getServiceLocator()->get('app_service_videoconf');
    }
    
    /**
     * @return \Application\Service\Item
     */
    public function getServiceItem()
    {
    	return $this->getServiceLocator()->get('app_service_item');
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
     * @return \Application\Service\ItemAssignment
     */
    public function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }
    
    /**
     * @return \Application\Service\ConversationUser
     */
    public function getServiceConversationUser()
    {
    	return $this->getServiceLocator()->get('app_service_conversation_user');
    }
    
    /**
     * @return \Application\Service\VideoconfConversation
     */
    public function getServiceVideoconfConversation()
    {
    	return $this->getServiceLocator()->get('app_service_videoconf_conversation');
    }
}
