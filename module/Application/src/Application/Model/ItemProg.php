<?php

namespace Application\Model;

use Application\Model\Base\ItemProg as BaseItemProg;

class ItemProg extends BaseItemProg
{
    
    protected $users;
    protected $item;
    
     public function exchangeArray(array &$data)
    {
        if ($this->isRepeatRelational()) {
            return;
        }
        
        parent::exchangeArray($data);

        $this->item = new Item($this);
        $this->item->exchangeArray($data);
    }

    public function setUsers($users)
    {
        $this->users = $users;

        return $this;
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function setItem($item)
    {
        $this->item = $item;

        return $this;
    }

    public function getItem()
    {
        return $this->item;
    }
}
