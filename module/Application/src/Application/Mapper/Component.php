<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\Predicate;

class Component extends AbstractMapper
{
    public function getList($dimension = null, $search = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'name', 'describe', 'dimension_id'))
               ->join(array('component_dimension'  => 'dimension'), 'component_dimension.id=component.dimension_id', array('id', 'name'));

        if (null !== $dimension) {
            if (is_numeric($dimension)) {
                $select->where(array('component.dimension_id' => $dimension));
            } else {
                $select->where(array('component_dimension.name' => $dimension));
            }
        }

        if (null !== $search) {
            $select->where(array(' ( component.name LIKE ?' => '%'.$search.'%'))->where(array('component.describe LIKE ? )' => '%'.$search.'%'), Predicate::OP_OR);
        }

        $select->where(array('component.deleted_date IS NULL'));

        return $this->selectWith($select);
    }
    
    public function getEqCq($school)
    {
        $sql = "SELECT 
                    AVG(`T`.`scale`) AS `value`,
                        `T`.`component` as `id`,
                        `T`.`dimension`,
                        `component`.`name` as label
                FROM
                    (SELECT 
                    `answer`.`peer_id` AS `peer`,
                        `component`.`id` AS `component`,
                        `dimension`.`id` AS `dimension`,
                        `program`.`school_id` AS `school`,
                        AVG(`scale`.`value`) AS `scale`
                FROM
                    `answer`
                INNER JOIN `scale` ON `scale`.`id` = `answer`.`scale_id`
                INNER JOIN `question` ON `question`.`id` = `answer`.`question_id`
                INNER JOIN `component` ON `component`.`id` = `question`.`component_id`
                INNER JOIN `dimension` ON `dimension`.`id` = `component`.`dimension_id`
                INNER JOIN `questionnaire_user` ON `questionnaire_user`.`id` = `answer`.`questionnaire_user_id`
                INNER JOIN `item` ON `item`.`id` = `questionnaire_user`.`questionnaire_id`
                INNER JOIN `course` ON `course`.`id` = `item`.`course_id`
                INNER JOIN `program` ON `program`.`id` = `course`.`program_id`
                WHERE
                    `answer`.`type` = 'peer'
                        AND `scale`.`value` <> 0
                GROUP BY `answer`.`peer_id` , `component`.`id` , `program`.`school_id`) AS T
            INNER JOIN `component` ON `component`.`id` = `T`.`component` 
            WHERE 
                `T`.`school` = :school
            GROUP BY `T`.`component` , `T`.`school`";
    
        return $this->selectNMPdo($sql, [':school' => $school]);
    }
}
