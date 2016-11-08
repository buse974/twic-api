<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Dal\Db\Sql\Select;
use Application\Model\Role as ModelRole;
use Zend\Db\Sql\Predicate\Predicate;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\Sql\Where;

class Submission extends AbstractMapper
{
    /**
     * @param int $id
     * 
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function checkGraded($id)
    {
        $select = new Select('submission_user');
        $select->columns(['has_graded' => new Expression('SUM(IF(submission_user.grade IS NULL,1,0)) = 0')])
               ->where(['submission_user.submission_id' => $id])
               ->group('submission_user.submission_id');

        $update = $this->tableGateway->getSql()->update();
        $update->set(['is_graded' => $select])
               ->where(['id' => $id]);

        return $this->updateWith($update);
    }

    /**
     * @param int $user
     * @param int $questionnaire
     * 
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getByUserAndQuestionnaire($user, $questionnaire)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id'))
            ->join('questionnaire', 'questionnaire.item_id=submission.item_id', array())
            ->join('submission_user', 'submission_user.submission_id=submission.id', array())
            ->where(array('submission_user.user_id' => $user))
            ->where(array('questionnaire.id' => $questionnaire));

        return $this->selectWith($select);
    }

    /**
     * @param int $user_id
     * @param int $conversation_id
     * 
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getByUserAndConversation($user_id, $conversation_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id'))
            ->join('sub_conversation', 'sub_conversation.submission_id=submission.id', array())
            ->join('submission_user', 'submission_user.submission_id=submission.id', array())
            ->where(array('submission_user.user_id' => $user_id))
            ->where(array('sub_conversation.conversation_id' => $conversation_id));

        return $this->selectWith($select);
    }

    /**
     * @param int $item_id
     * @param int $user_id
     * @param int $submission_id
     * @param int $group_id
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getSubmissionUser($item_id = null, $user_id = null, $submission_id = null, $group_id = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'item_id', 'submission$submit_date' => new Expression('DATE_FORMAT(submission.submit_date, "%Y-%m-%dT%TZ")')))
            ->join('submission_user', 'submission_user.submission_id=submission.id',
                ['user_id', 'grade', 'submit_date', 'overwritten', 'start_date', 'end_date']);

        if (null !== $submission_id) {
            $select->where(array('submission.id' => $submission_id));
        } else {
            if (null !== $group_id && null !== $item_id) {
                $select->where(array('submission.group_id' => $group_id))
                ->where(array('submission.item_id' => $item_id));
            } elseif (null !== $user_id && null !== $item_id) {
                $select->where(array('submission_user.user_id' => $user_id))
                ->where(array('submission.item_id' => $item_id));
            } elseif (null !== $item_id) {
                $select->where(array('submission.item_id' => $item_id));
            }
        }

        return $this->selectWith($select);
    }

    /**
     * @param int $item_id
     * @param int $user_id
     * @param int $submission_id
     * 
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function get($item_id = null, $user_id = null, $submission_id = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns([
            'submission$id' => new Expression('submission.id'),
            'item_id',
            'group_name',
            'group_id',
            'submission$submit_date' => new Expression('DATE_FORMAT(submission.submit_date, "%Y-%m-%dT%TZ")'),
            'is_graded',
            'submission$nbr_comments' => $this->getSelectNbrComments(),
        ])
            ->join('submission_user', 'submission_user.submission_id=submission.id', [])
            ->quantifier('DISTINCT');

        if (null !== $submission_id) {
            $select->where(array('submission.id' => $submission_id));
        }
        if (null !== $user_id) {
            $select->where(array('submission_user.user_id' => $user_id));
        }
        if (null !== $item_id) {
            $select->where(array('submission.item_id' => $item_id));
        }
        syslog(1, $this->printSql($select));
        return $this->selectWith($select);
    }

    public function getWithItem($submission_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns([
            'submission$id' => new Expression('submission.id'),
            'item_id',
            'group_name',
            'group_id',
            'submission$submit_date' => new Expression('DATE_FORMAT(submission.submit_date, "%Y-%m-%dT%TZ")'),
            'is_graded',
            'submission$nbr_comments' => $this->getSelectNbrComments(),
        ])
        ->join('submission_user', 'submission_user.submission_id=submission.id', [])
        ->join('item', 'item.id=submission.item_id', ['id', 'title', 'type', 'course_id', 'start', 'end', 'cut_off'])
        ->quantifier('DISTINCT');

        if (null !== $submission_id) {
            $select->where(array('submission.id' => $submission_id));
        }

        return $this->selectWith($select);
    }

    /**
     * @return \Zend\Db\Sql\Select
     */
    private function getSelectNbrComments()
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('submission$nbr_comments' => new Expression('COUNT(true)')))
                 ->join('submission_comments', 'submission.id = submission_comments.submission_id', [])
                 ->where(array('submission.id=`submission$id`'));

        return $select;
    }

    public function getListToGrade($user_id, $item_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'item_id', 'group_id', 'group_name', 'is_graded', 'submission$submit_date' => new Expression('DATE_FORMAT(submission.submit_date, "%Y-%m-%dT%TZ")')))
            ->join(['submission_submission_pg' => 'submission_pg'], 'submission_submission_pg.submission_id=submission.id', ['has_graded'])
            ->join('sub_thread', 'sub_thread.submission_id=submission.id', ['submission$thread_id' => 'thread_id'], $select::JOIN_LEFT)
            ->join('item', 'item.id=submission.item_id', ['id', 'item$start' => new Expression('DATE_FORMAT(item.start, "%Y-%m-%dT%TZ")'), 'item$end' => new Expression('DATE_FORMAT(item.end, "%Y-%m-%dT%TZ")'), 'item$cut_off' => new Expression('DATE_FORMAT(item.cut_off, "%Y-%m-%dT%TZ")'), 'describe', 'type', 'is_grouped', 'title'])
            ->join(['submission_item_course' => 'course'], 'submission_item_course.id=item.course_id', ['id', 'title'])
            ->join(['submission_item_program' => 'program'], 'submission_item_program.id=submission_item_course.program_id', ['name'])
            ->where(['item.is_complete IS TRUE'])
            ->where(['item.id' => $item_id])
            ->where(['submission_submission_pg.user_id' => $user_id])
            ->where(['submission_item_course.deleted_date IS NULL'])
            ->where(['submission_item_program.deleted_date IS NULL']);

        return $this->selectWith($select);
    }

    public function getListStudent($user_id, $type = null, $course = null, $started = null, $submitted = null, $graded = null, $late = null, $search = null, $tograde = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'item_id', 'group_id', 'group_name', 'is_graded', 'submission$submit_date' => new Expression('DATE_FORMAT(submission.submit_date, "%Y-%m-%dT%TZ")')))
            ->join('submission_user', 'submission_user.submission_id=submission.id', ['grade'])
            ->join('sub_thread', 'sub_thread.submission_id=submission.id', ['submission$thread_id' => 'thread_id'], $select::JOIN_LEFT)
            ->join('item', 'item.id=submission.item_id', ['id', 'item$start' => new Expression('DATE_FORMAT(item.start, "%Y-%m-%dT%TZ")'), 'item$end' => new Expression('DATE_FORMAT(item.end, "%Y-%m-%dT%TZ")'), 'item$cut_off' => new Expression('DATE_FORMAT(item.cut_off, "%Y-%m-%dT%TZ")'), 'describe', 'type', 'is_grouped', 'title', 'coefficient'])
            ->join(['submission_item_course' => 'course'], 'submission_item_course.id=item.course_id', ['id', 'title'])
            ->join(['submission_item_program' => 'program'], 'submission_item_program.id=submission_item_course.program_id', ['name'])
            ->where(['item.is_complete IS TRUE'])
            ->where(["item.type <> 'HANGOUT'"])
            ->where(['submission_item_course.deleted_date IS NULL'])
            ->where(['submission_item_program.deleted_date IS NULL'])
            ->where(['submission_user.user_id' => $user_id]);

        if (null !== $search) {
            $select->where(array('( submission_item_course.title LIKE ?' => '%'.$search.'%'))
                   ->where(array('submission_item_program.name LIKE ? )' => '%'.$search.'%'), Predicate::OP_OR);
        }
        if (!empty($type)) {
            if(in_array('A', $type)) {
                $select->where->NEST->in('item.type', $type)->OR->NEST->literal("item.is_grouped IS FALSE AND item.type = 'IA'")->UNNEST->UNNEST;
            }elseif(in_array('GA', $type)) {
                $select->where->NEST->in('item.type', $type)->OR->NEST->literal("item.is_grouped IS TRUE AND item.type = 'IA'")->UNNEST->UNNEST;
            }elseif(in_array('GA', $type) && in_array('A', $type) && !in_array('IA', $type) ) {
                $type[] = 'IA';
                $select->where->in('item.type', $type);
            }else{
                $select->where->in('item.type', $type);
            }
        }
        if (!empty($course)) {
            $select->where(array('submission_item_course.id' => $course));
        }
        if (true === $started) {
            $select->where(array('item.start < UTC_TIMESTAMP()'));
        }
        if (true === $submitted) {
            $select->where(array('submission.submit_date IS NOT NULL'));
        }
        if (true === $graded) {
            $select->where(array('submission.is_graded IS TRUE'));
        }
        if (true === $late) {
            $select->where(array('item.end < UTC_TIMESTAMP() AND submission.submit_date IS NULL'));
        }
        if (true === $tograde) {
            $select->join('opt_grading', 'opt_grading.item_id = item.id')
                   ->where(array('opt_grading.mode <> "none"'));
        }
        
        return $this->selectWith($select);
    }

    public function getList($item_id, $user_id)
    {
        $sql = 'SELECT  
                    `submission`.`submit_date` AS `submission$submit_date`,
                     `submission`.`id` AS `submission$id`,
                     `group`.`id` AS `submission$group_id`,
                     `group`.`name` AS `submission$group_name`,
                     `submission`.`id` AS `submission$id`,
                     `course_user_relation`.`user_id` as `submission_user$user_id`,
                     `submission_user`.`grade` as `submission_user$grade`,
                     `submission_user`.`submit_date` as `submission_user$submit_date`,
                     `user`.`firstname` as `user$firstname`,
                     `user`.`lastname` as `user$lastname`,
                     `user`.`nickname` as `user$nickname`,
                     `user`.`avatar` as `user$avatar`,
                     `user`.`id` as `user$id`
                FROM
                    `item` 
                        LEFT JOIN
                    `ct_group` ON `ct_group`.`item_id` = `item`.`id`
                        LEFT JOIN
                    `group_user` ON `group_user`.`group_id` = `ct_group`.`group_id`
                        LEFT JOIN
                    `course_user_relation` ON `item`.`course_id` = `course_user_relation`.`course_id`
                        AND `item`.`set_id` IS NULL
                        AND ((`group_user`.`user_id` = `course_user_relation`.`user_id` AND `ct_group`.`item_id` IS NOT NULL) OR `ct_group`.`item_id` IS NULL)
                        AND `course_user_relation`.`user_id` IN (SELECT `user_id` FROM `user_role` WHERE `role_id`='.ModelRole::ROLE_STUDENT_ID.' )
                        LEFT JOIN
                    `user_role` ON `user_role`.`user_id`=`course_user_relation`.`user_id`
                        LEFT JOIN
                    `set_group` ON `item`.`set_id` = `set_group`.`set_id`
                        AND ((`ct_group`.`group_id` = `set_group`.`group_id`)
                        OR `ct_group`.`item_id` IS NULL)
                        LEFT JOIN
                    `group` ON `group`.`id` = `set_group`.`group_id`
                         LEFT JOIN 
                    `submission_user` ON `submission_user`.`user_id` = `course_user_relation`.`user_id`
                        AND `submission_user`.`submission_id` IN (SELECT `id` FROM `submission` WHERE `submission`.`item_id`=:item )
                        LEFT JOIN
                    `submission` ON `submission`.`item_id`=`item`.`id` AND (`submission`.`id` = `submission_user`.`submission_id`)
                        OR (`submission`.`group_id` = `set_group`.`group_id`) 
						LEFT JOIN 
					`user` ON `course_user_relation`.`user_id`=`user`.`id`
                        LEFT JOIN
	                `submission_comments` ON `submission_comments`.`submission_id`=`submission`.`id`
                WHERE item.id = :item2   
                GROUP BY `submission`.`id`, `submission_comments`.`submission_id`, `group`.`id`, `course_user_relation`.`user_id`';

        return $this->selectPdo($sql, [':item' => $item_id, ':item2' => $item_id]);
    }

    /**
     * @param int $user
     *
     * @return \Zend\Db\Sql\Select
     */
    private function getSelectContactState($user)
    {
        $select = new Select('user');
        $select->columns(array('user$contact_state' => new Expression(
            'IF(contact.accepted_date IS NOT NULL, 3,
	         IF(contact.request_date IS NOT  NULL AND contact.requested <> 1, 2,
		     IF(contact.request_date IS NOT  NULL AND contact.requested = 1, 1,0)))')))
                 ->join('contact', 'contact.contact_id = user.id', array())
                 ->where(array('user.id=`user$id`'))
                 ->where(['contact.user_id' => $user]);

        return $select;
    }
}
