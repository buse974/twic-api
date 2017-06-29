<?php

namespace Application\Model;

use Application\Model\Base\ItemUser as BaseItemUser;

class ItemUser extends BaseItemUser
{
  protected $group;

  public function exchangeArray(array &$data)
  {
      parent::exchangeArray($data);

      $this->group = $this->requireModel('app_model_group', $data);
  }


    /**
     * Get the value of Group
     *
     * @return mixed
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Set the value of Group
     *
     * @param mixed group
     *
     * @return self
     */
    public function setGroup($group)
    {
        $this->group = $group;

        return $this;
    }

}
