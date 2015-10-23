<?php
namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Dal\Db\Sql\Select;
use Zend\Db\Sql\Predicate\Predicate;
use Dal\Db\Sql\Dal\Db\Sql;

class Component extends AbstractMapper
{

    public function getList($dimension = null, $search = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id','name','describe','dimension_id'))->join(array('component_dimension' => 'dimension'), 'component_dimension.id=component.dimension_id', array('id','name'));
        
        if (null !== $dimension) {
            if (is_numeric($dimension)) {
                $select->where(array('component.dimension_id' => $dimension));
            } else {
                $select->where(array('component_dimension.name' => $dimension));
            }
        }
        
        if (null !== $search) {
            $select->where(array(' ( component.name LIKE ?' => '%' . $search . '%'))->where(array('component.describe LIKE ? )' => '%' . $search . '%'), Predicate::OP_OR);
        }
        
        $select->where(array('component.deleted_date IS NULL'));
        
        return $this->selectWith($select);
    }

    public function getEqCq($school, $gender = null, $nationality = null, $origin = null, $program = null)
    {
        $params = [':school' => $school];
        $req = "SELECT 
                    `answer`.`peer_id` AS `peer`, 
                    `component`.`id` AS `component`, 
                    `dimension`.`id` AS `dimension`, 
                    `program`.`school_id` AS `school`, 
                    AVG(`scale`.`value`) AS `scale` 
                FROM 
                    `answer` 
                INNER JOIN `user` ON `user`.`id` = `answer`.`peer_id` 
                INNER JOIN `scale` ON `scale`.`id` = `answer`.`scale_id` 
                INNER JOIN `question` ON `question`.`id` = `answer`.`question_id` 
                INNER JOIN `component` ON `component`.`id` = `question`.`component_id` 
				INNER JOIN `dimension` ON `dimension`.`id` = `component`.`dimension_id` 
                INNER JOIN `questionnaire_user` ON `questionnaire_user`.`id` = `answer`.`questionnaire_user_id` 
				INNER JOIN `questionnaire` ON `questionnaire`.`id` = `questionnaire_user`.`questionnaire_id` 
				INNER JOIN `item` ON `item`.`id` = `questionnaire`.`item_id` 
				INNER JOIN `course` ON `course`.`id` = `item`.`course_id` 
				INNER JOIN `program` ON `program`.`id` = `course`.`program_id` 
                WHERE 
                    `answer`.`type` = 'peer' 
                        AND `scale`.`value` <> 0 ";
        
        if (null !== $gender) {
            $req .= " AND user.gender=:gender ";
            $params[':gender'] = $gender;
        }
        
        if (null !== $nationality) {
            if (! is_array($nationality)) {
                $nationality = [$nationality];
            }
            $v = [];
            $i = 1;
            foreach ($nationality as $n) {
                $v[] = ':n' . $i;
                $params[':n' . $i ++] = $n;
            }
            $req .= " AND user.nationality IN (" . implode(",", $v) . ") ";
        }
        
        if (null !== $origin) {
            if (! is_array($origin)) {
                $origin = [$origin];
            }
            
            $v = [];
            $i = 1;
            foreach ($origin as $n) {
                $v[] = ':o' . $i;
                $params[':o' . $i ++] = $n;
            }
            $req .= " AND user.origin IN (" . implode(",", $v) . ") ";
        }
        
        if (null !== $program) {
            if (! is_array($program)) {
                $program = [$program];
            }
            $v = [];
            $i = 1;
            foreach ($program as $n) {
                $v[] = ':p' . $i;
                $params[':p' . $i ++] = $n;
            }
            $req .= " AND program.id IN (" . implode(",", $v) . ") ";
        }
        
        $req .= " GROUP BY `answer`.`peer_id` , `component`.`id` , `program`.`school_id`";
        
        $sql = "SELECT 
                    AVG(`T`.`scale`) * 20 AS `average`,
                        `T`.`component` as `id`,
                        `T`.`dimension`,
                        `component`.`name` as label
                FROM
                    (" . $req . ") AS T
            INNER JOIN `component` ON `component`.`id` = `T`.`component` 
            WHERE 
                `T`.`school` = :school
            GROUP BY `T`.`component` , `T`.`school`";
        
        return $this->selectNMPdo($sql, $params);
    }

    public function getEqCqStat($school, $gender = null, $nationality = null, $origin = null, $program = null)
    {
        $params = [':school' => $school];
        $req = "SELECT 
                	AVG(FLOOR((UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(user.birth_date))/31557600)) AS avgage,
                    MAX(FLOOR((UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(user.birth_date))/31557600)) AS maxage,
                    MIN(FLOOR((UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(user.birth_date))/31557600)) AS minage,
                    COUNT(true) as total,
                    GROUP_CONCAT(DISTINCT user.gender SEPARATOR '|') as genre,
                    GROUP_CONCAT(DISTINCT user.nationality SEPARATOR '|') as nationality,
                    GROUP_CONCAT(DISTINCT user.origin SEPARATOR '|') as origin
                FROM
                    `answer`
                INNER JOIN `user` ON `user`.`id` = `answer`.`peer_id`
                INNER JOIN `scale` ON `scale`.`id` = `answer`.`scale_id`
                INNER JOIN `question` ON `question`.`id` = `answer`.`question_id`
                INNER JOIN `component` ON `component`.`id` = `question`.`component_id`
				INNER JOIN `dimension` ON `dimension`.`id` = `component`.`dimension_id`
                INNER JOIN `questionnaire_user` ON `questionnaire_user`.`id` = `answer`.`questionnaire_user_id`
				INNER JOIN `questionnaire` ON `questionnaire`.`id` = `questionnaire_user`.`questionnaire_id`
				INNER JOIN `item` ON `item`.`id` = `questionnaire`.`item_id`
				INNER JOIN `course` ON `course`.`id` = `item`.`course_id`
				INNER JOIN `program` ON `program`.`id` = `course`.`program_id`
                WHERE
                    `answer`.`type` = 'peer'
                        AND `scale`.`value` <> 0 ";
        
        if (null !== $gender) {
            $req .= " AND user.gender=:gender ";
            $params[':gender'] = $gender;
        }
        if (null !== $nationality) {
            if (! is_array($nationality)) {
                $nationality = [$nationality];
            }
            $v = [];
            $i = 1;
            foreach ($nationality as $n) {
                $v[] = ':n' . $i;
                $params[':n' . $i ++] = $n;
            }
            $req .= " AND user.nationality IN (" . implode(",", $v) . ") ";
        }
        if (null !== $origin) {
            if (! is_array($origin)) {
                $origin = [$origin];
            }
            
            $v = [];
            $i = 1;
            foreach ($origin as $n) {
                $v[] = ':o' . $i;
                $params[':o' . $i ++] = $n;
            }
            $req .= " AND user.origin IN (" . implode(",", $v) . ") ";
        }
        if (null !== $program) {
            if (! is_array($program)) {
                $program = [$program];
            }
            $v = [];
            $i = 1;
            foreach ($program as $n) {
                $v[] = ':p' . $i;
                $params[':p' . $i ++] = $n;
            }
            $req .= " AND program.id IN (" . implode(",", $v) . ") ";
        }
        
        return $this->selectNMPdo($req, $params);
    }
}
