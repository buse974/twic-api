<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Application\Model\Role as ModelRole;

class ItemProgUser extends AbstractMapper
{
    public function insertStudent($u, $ip)
    {
        $sql = "INSERT INTO `item_prog_user` (`user_id`, `item_prog_id`) 
                SELECT 
                    `user`.`id` AS `id`, :ip AS `ip`
                FROM
                    `user`
                        INNER JOIN
                    `user_role` ON `user_role`.`user_id` = `user`.`id`
                WHERE
                    user_role.role_id = :role
                        AND `user_role`.`user_id` = :u 
                	AND NOT EXISTS (SELECT * FROM `item_prog_user` WHERE `user_id` = :u1 AND `item_prog_id` = :ip1)";
        
        return $this->requestPdo($sql, array(
            ':ip' => $ip,
            ':role' => ModelRole::ROLE_STUDENT_ID,
            ':u' => $u,
            ':u1' => $u,
            ':ip1' => $ip,
        ));
    }
}
