<?php
namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Application\Model\Role as ModelRole;
use Zend\Db\Sql\Predicate\Expression;

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
        
        return $this->requestPdo($sql, array(':ip' => $ip,':role' => ModelRole::ROLE_STUDENT_ID,':u' => $u,':u1' => $u,':ip1' => $ip));
    }

    public function getStartedConference($user)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->columns(array('item_prog_user$started_date' => new Expression("DATE_FORMAT(item_prog_user.started_date, '%Y-%m-%dT%TZ') ")))
            ->join('item_prog', 'item_prog_user.item_prog_id=item_prog.id', array('id','item_id', 'item_prog$start_date' => new Expression("DATE_FORMAT(item_prog.start_date, '%Y-%m-%dT%TZ') ")))
            ->join('item', 'item_prog.item_id=item.id', array('id','type'))
            ->join('questionnaire', 'questionnaire.item_id=item_prog.item_id', array('id','created_date'), $select::JOIN_LEFT)
            ->where(array('item_prog_user.user_id' => $user))
            ->where(array('item_prog_user.finished_date IS NULL'));
        
        //echo $this->printSql($select);
        
        return $this->selectWith($select);
    }
}
