<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class SubscriptionUser extends AbstractModel
{
    protected $libelle;
    protected $user_id;

    protected $prefix = 'subscription_user';

    public function getLibelle()
    {
        return $this->libelle;
    }

    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }
}
