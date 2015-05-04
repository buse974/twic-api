<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class ProgramUserRelation extends AbstractMapper
{
    public function insertUserProgram($p,$u)
    {
        $req = "INSERT INTO `program_user_relation` (`program_id`,`user_id`) SELECT * FROM (select " . $p ." as p," . $u ." as u) as t WHERE NOT EXISTS 
(SELECT * FROM program_user_relation WHERE program_id=" . $p ." AND user_id=" . $u .") LIMIT 1;";
        
        return $this->requestPdo($req);
    }
}
