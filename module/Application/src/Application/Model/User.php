<?php

namespace Application\Model;

use Application\Model\Base\User as BaseUser;

class User extends BaseUser
{
    protected $school;
    protected $roles;
    protected $available;
    protected $selected;
    protected $contact_state;
    protected $contacts_count;
    protected $gender;
    protected $nationality;
    protected $origin;
    protected $item_prog_user;
    protected $role;
    protected $nb_user;
    protected $role_id;

    public function exchangeArray(array &$data)
    {
        if ($this->isRepeatRelational()) {
            return;
        }

        parent::exchangeArray($data);

        $this->role = $this->requireModel('app_model_role', $data);
        $this->school = $this->requireModel('app_model_school', $data);
        $this->nationality = $this->requireModel('addr_model_country', $data, 'nationality');
        $this->origin = $this->requireModel('addr_model_country', $data, 'origin');
        $this->item_prog_user = $this->requireModel('app_model_item_prog_user', $data);
    }

    public function getRoleId()
    {
        return $this->role_id;
    }
    
    public function setRoleId($role_id)
    {
        $this->role_id = $role_id;
    
        return $this;
    }
    
    public function getNbUser()
    {
        return $this->nb_user;
    }
    
    public function setNbUser($nb_user)
    {
        $this->nb_user = $nb_user;
    
        return $this;
    }
    
    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    public function getItemProgUser()
    {
        return $this->item_prog_user;
    }

    public function setItemProgUser($item_prog_user)
    {
        $this->item_prog_user = $item_prog_user;

        return $this;
    }

    public function getOrigin()
    {
        return $this->origin;
    }

    public function setOrigin($origin)
    {
        $this->origin = $origin;

        return $this;
    }
    public function getNationality()
    {
        return $this->nationality;
    }

    public function setNationality($nationality)
    {
        $this->nationality = $nationality;

        return $this;
    }
    public function getGender()
    {
        return $this->gender;
    }

    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    public function getContactState()
    {
        return $this->contact_state;
    }

    public function setContactState($contact_state)
    {
        $this->contact_state = ($contact_state===null) ? 0:$contact_state;

        return $this;
    }

    public function getContactsCount()
    {
        return $this->contacts_count;
    }

    public function setContactsCount($contacts_count)
    {
        $this->contacts_count = $contacts_count;

        return $this;
    }

    public function setSchool($school)
    {
        $this->school = $school;

        return $this;
    }

    public function getSchool()
    {
        return $this->school;
    }

    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function setAvailable($available)
    {
        $this->available = $available;

        return $this;
    }

    public function getAvailable()
    {
        return $this->available;
    }

    public function setSelected($selected)
    {
        $this->selected = $selected;

        return $this;
    }

    public function getSelected()
    {
        return $this->selected;
    }
}
