<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class ModuleSessionUser extends AbstractModel
{
    protected $user_id;
    protected $module_session_id;

    protected $prefix = 'module_session_user';

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getModuleSessionId()
    {
        return $this->module_session_id;
    }

    public function setModuleSessionId($module_session_id)
    {
        $this->module_session_id = $module_session_id;

        return $this;
    }
}
