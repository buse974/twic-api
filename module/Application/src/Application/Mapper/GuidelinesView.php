<?php
namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class GuidelinesView extends AbstractMapper
{

    public function view($state, $user)
    {
        $sql = "INSERT INTO `guidelines_view`
                (`state`, `user_id`) SELECT :state as state, :user as user_id FROM DUAL 
                WHERE NOT EXISTS ( select * from guidelines_view WHERE  state=:state2 AND user_id=:user2)";
        
        return $this->requestPdo($sql, [
            ':user' => $user,
            ':user2' => $user,
            ':state' => $state,
            ':state2' => $state]);
    }
}