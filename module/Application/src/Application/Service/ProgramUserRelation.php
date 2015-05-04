<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class ProgramUserRelation extends AbstractService
{
    public function add($user, $program)
    {
        $ret = array();
        
        foreach ($user as $u) {
            foreach ($program as $p) {
                $ret[$u][$p] = $this->getMapper()->insertUserProgram($p,$u);
            }
        }
        
        return $ret;
    }
    
    public function deleteByUser($user)
    {
        return $this->getMapper()->delete($this->getModel()->setUserId($user));
    }
}
