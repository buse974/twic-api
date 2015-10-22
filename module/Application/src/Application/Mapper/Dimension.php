<?php
namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class Dimension extends AbstractMapper
{

    /**
     *
     * @param string $search            
     *
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getList($search = null)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->columns(array('id','name','describe','deleted_date'));
        
        if (null !== $search) {
            $select->where(array('name LIKE ? ' => '%' . $search . '%'));
        }
        $select->where(array('deleted_date IS NULL'));
        
        return $this->selectWith($select);
    }

    public function getEqCq($school)
    {
        $sql = "SELECT 
                AVG(`T`.`scale`) AS `scale`,
                `dimension`.`id`,
                `dimension`.`name`
                FROM
                (SELECT 
                    AVG(`T`.`scale`) AS `scale`,
                        `T`.`peer`,
                        `T`.`component`,
                        `T`.`dimension`,
                        `T`.`school`
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
				INNER JOIN `questionnaire` ON `questionnaire`.`id` = `questionnaire_user`.`questionnaire_id`
				INNER JOIN `item` ON `item`.`id` = `questionnaire`.`item_id`
				INNER JOIN `course` ON `course`.`id` = `item`.`course_id`
				INNER JOIN `program` ON `program`.`id` = `course`.`program_id`
                WHERE
                    `answer`.`type` = 'peer'
                        AND `scale`.`value` <> 0
                GROUP BY `answer`.`peer_id` , `component`.`id` , `program`.`school_id`) AS T
                GROUP BY `T`.`dimension`,`T`.`peer`,`T`.`school`) AS T
                JOIN `dimension` ON  `dimension`.`id`=`T`.`dimension`
            	WHERE `T`.`school`=:school
                GROUP BY `T`.`dimension` , `T`.`school`";
        
        return $this->selectNMPdo($sql, [':school' => $school]);
    }
}
