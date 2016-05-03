<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\NotIn;
use Zend\Db\Sql\Predicate\Predicate;
use Zend\Db\Sql\Predicate\Expression;
use Dal\Db\Sql\Select;
use Application\Model\Role as ModelRole;

class Item extends AbstractMapper
{
    public function get($id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id', 'title', 'type', 'course_id', 'grading_policy_id', 'parent_id', 'order_id', 'has_submission',
            'item$start' => new Expression('DATE_FORMAT(item.start, "%Y-%m-%dT%TZ")'),
            'item$end' => new Expression('DATE_FORMAT(item.end, "%Y-%m-%dT%TZ")'),
            'item$cut_off' => new Expression('DATE_FORMAT(item.cut_off, "%Y-%m-%dT%TZ")')
        ])
            ->join('course', 'course.id=item.course_id', array('id', 'title'))
            ->join('program', 'program.id=course.program_id', array('id', 'name'))
            ->where(array('item.id' => $id));

        return $this->selectWith($select);
    }
    
    public function getAllow($id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns([
            'id', 
            'title', 
            'describe', 
            'duration', 
            'type', 
            'course_id', 
            'grading_policy_id',
            'set_id', 
            'parent_id', 
            'order_id', 
            'has_submission',
            'item$start' => new Expression('DATE_FORMAT(item.start, "%Y-%m-%dT%TZ")'),
            'item$end' => new Expression('DATE_FORMAT(item.end, "%Y-%m-%dT%TZ")'),
            'item$cut_off' => new Expression('DATE_FORMAT(item.cut_off, "%Y-%m-%dT%TZ")')
        ])
            ->join('course', 'course.id=item.course_id', array('id', 'title'))
            ->join('program', 'program.id=course.program_id', array('id', 'name'))
            ->join('opt_grading', 'item.id=opt_grading.item_id', array('mode', 'has_pg', 'pg_nb', 'pg_auto', 'pg_due_date', 'pg_can_view', 'user_can_view', 'pg_stars'), $select::JOIN_LEFT)
            ->where(array('item.id' => $id));
    
        return $this->selectWith($select);
    }

    public function getListGrade($me, $programs, $courses, $type, $notgraded, $newMessage, $filter, $item_prog, $user)
    {
        $select = $this->tableGateway->getSql()->select();

        $select_new_message = new Select('item_assignment_comment');
        $select_new_message->columns(array('rrr' => new Expression('COUNT(1)')))
            ->where(array('item_assignment_comment.item_assignment_id=item_item_prog_item_assignment.id'))
            ->where(array('item_assignment_comment.read_date IS NULL'));

        $select->columns(array('id', 'title', 'item$new_message' => $select_new_message))
            ->join('course', 'course.id=item.course_id', array('id', 'title'))
            ->join('program', 'program.id=course.program_id', array('id', 'name'))
            ->join('item_prog', 'item_prog.item_id=item.id', array('id', 'item_prog$due_date' => new Expression('DATE_FORMAT(due_date, "%Y-%m-%dT%TZ")'), 'item_prog$start_date' => new Expression('DATE_FORMAT(start_date, "%Y-%m-%dT%TZ")')))
            ->join('item_prog_user', 'item_prog_user.item_prog_id = item_prog.id', array())
            ->join('item_assignment_relation', 'item_assignment_relation.item_prog_user_id = item_prog_user.id', array(), $select::JOIN_LEFT)
            ->join(array('item_item_prog_item_assignment' => 'item_assignment'), 'item_item_prog_item_assignment.id=item_assignment_relation.item_assignment_id', array('id', 'item_item_prog_item_assignment$submit_date' => new Expression('DATE_FORMAT(submit_date, "%Y-%m-%dT%TZ")')), $select::JOIN_LEFT)
            ->join(array('item_item_prog_item_grading' => 'item_grading'), 'item_item_prog_item_grading.item_prog_user_id=item_prog_user.id', array('grade'), $select::JOIN_LEFT)
            ->join('grading', 'item_item_prog_item_grading.grade BETWEEN grading.min AND grading.max', array('item_item_prog_item_grading$letter' => 'letter'), $select::JOIN_LEFT)
            ->join('user', 'item_prog_user.user_id=user.id', array(), $select::JOIN_LEFT)
            ->where(array("item.type <> 'LC'"))
            ->where(array('item_prog.start_date < UTC_TIMESTAMP'))
            ->order(array('item_item_prog_item_assignment.submit_date' => 'DESC'))
            ->quantifier('DISTINCT');
        if (null !== $programs) {
            $select->where(array('program.id' => $programs));
        }
        if ($courses !== null) {
            $select->where(array('course.id' => $courses));
        }
        if ($type !== null) {
            $select->where(array('item.type' => $type));
        }
        if (isset($filter['search'])) {
            $select->where(array(' ( user.firstname LIKE ?' => $filter['search'].'%'))->where(array('user.lastname LIKE ? ) ' => $filter['search'].'%'), Predicate::OP_OR);
        }

        if ($notgraded === true || $newMessage === true) {
            if ($newMessage === true) {
                $select->join('item_assignment_comment', 'item_assignment_comment.item_assignment_id=item_item_prog_item_assignment.id', array(), $select::JOIN_LEFT)->where(array('( ( item_assignment_comment.id IS NOT NULL AND item_assignment_comment.read_date IS NULL) '));
            } else {
                $select->where(array('( 0'));
            }
            if ($notgraded === true) {
                $select->where(array(' item_item_prog_item_grading.id IS NULL )'), Predicate::OP_OR);
            } else {
                $select->where(array(' 0 )'), Predicate::OP_OR);
            }
        }

        if (!array_key_exists(ModelRole::ROLE_STUDENT_ID, $me['roles'])) {
            $select->where(array('item_item_prog_item_assignment.submit_date IS NOT NULL'));
        } else {
            $select->where(array('item_prog_user.user_id' => $me['id']));
        }
        if (array_key_exists(ModelRole::ROLE_ACADEMIC_ID, $me['roles'])) {
            $select->where(array('program.school_id' => $me['school']['id']));
        } elseif (array_key_exists(ModelRole::ROLE_INSTRUCTOR_ID, $me['roles'])) {
            $select->join('course_user_relation', 'course_user_relation.course_id = course.id', array())->where(array('course_user_relation.user_id' => $me['id']));
        }
        if ($item_prog !== null) {
            $select->where(array('item_prog.id' => $item_prog));
        }
        if ($user !== null) {
            $select->where(array('item_prog_user.user_id' => $user));
        }

        return $this->selectWith($select);
    }

    public function getListRecord($course, $user, $is_student = false)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('id', 'title', 'type'))
            ->join('submission', 'submission.item_id=item.id', array(), $select::JOIN_INNER)
            ->join('videoconf', 'submission.id=videoconf.submission_id', array(), $select::JOIN_INNER)
            ->join('videoconf_archive', 'videoconf.id=videoconf_archive.videoconf_id', array(), $select::JOIN_INNER)
            ->where(array('videoconf_archive.archive_link IS NOT NULL'))
            ->where(array('item.course_id' => $course))
            ->group('item.id');

        if ($is_student !== false) {
            $select->join('submission_user', 'submission.id=item_prog_user.submission_id', array(), $select::JOIN_INNER)
                ->where(array('submission_user.user_id' => $user));
        }

        return $this->selectWith($select);
    }

    /**
     * Get Last parent id.
     *
     * @param int $course
     *
     * @return int
     */
    public function selectLastOrderId($course = null, $id = null, $parent = null)
    {
        if ($course === null && $id = null) {
            throw new \Exception('Course and id are null');
        }
        if ($course === null) {
            $course = $this->tableGateway->getSql()->select();
            $course->columns(array('course_id'))->where(array('id' => $id));
        }

        $select = $this->tableGateway->getSql()->select();
        $subselect = $this->tableGateway->getSql()->select();

        $subselect->columns(array('order_id'))
            ->where(array('order_id IS NOT NULL'))
            ->where(array('course_id' => $course));
            if($parent === null) {
            	$subselect->where(array('parent_id IS NULL'));
            } else {
            	$subselect->where(array('parent_id' => $parent));
            }
            
        $select->columns(array('id'))
            ->where(array(new NotIn('id', $subselect)))
            ->where(array('course_id' => $course));
        
            if($parent === null) {
            	$select->where(array('parent_id IS NULL'));
            } else {
            	$select->where(array('parent_id' => $parent));
            }

        $res = $this->selectWith($select);

        return (($res->count() > 0) ? $res->current()->getId() : null);
    }
    
    public function  getListSubmissions($school_id, $type = null, $program = null, $course = null, $due = null, $notgraded = null, $search = null)
    {
        $sql = 'SELECT 
                    COUNT(1) AS `item$due`,
                    `item`.`id` AS `item$id`,
                    `item`.`title` AS `item$title`,
                    `item`.`type` AS `item$type`,
                    `item`.`start` AS `item$start`,
                    `item`.`end` AS `item$end`,
                    IF(submission.is_graded IS NULL,0,submission.is_graded) AS `item$graded`,
                    IF(submission.submit_date IS NULL, 0, 1) AS `item$submitted`,
                    `course`.`title` AS `course$title`,
                    `program`.`name` AS `program$name`
                FROM
                    `item`
                        LEFT JOIN
                    `ct_group` ON `ct_group`.`item_id` = `item`.`id`
                        LEFT JOIN
                    `group_user` ON `group_user`.`group_id` = `ct_group`.`group_id`
                        LEFT JOIN
                    `course_user_relation` ON `item`.`course_id` = `course_user_relation`.`course_id`
                        AND `item`.`set_id` IS NULL
                        AND ((`group_user`.`user_id` = `course_user_relation`.`user_id`
                        AND `ct_group`.`item_id` IS NOT NULL)
                        OR `ct_group`.`item_id` IS NULL)
                        LEFT JOIN
                    `set_group` ON `item`.`set_id` = `set_group`.`set_id`
                        AND ((`ct_group`.`group_id` = `set_group`.`group_id`)
                        OR `ct_group`.`item_id` IS NULL)
                        LEFT JOIN
                    `submission_user` ON `submission_user`.`user_id` = `course_user_relation`.`user_id`
                        LEFT JOIN
                    `submission` ON (`submission`.`id` = `submission_user`.`submission_id`)
                        OR (`submission`.`group_id` = `set_group`.`group_id`)
                        INNER JOIN
                    `course` ON `item`.`course_id` = `course`.`id`
                        INNER JOIN
                    `program` ON `program`.`id` = `course`.`program_id`
                WHERE ';
        
        
        $where=[];
        $val=[];
        if (!empty($course)) {
            $val[':c'] = $course;
            $where[] = 'course.id=:c';
        }
        if (!empty($program)) {
            $val[':p'] = $program;
            $where[] = 'program.id=:p';
        }
        if (!empty($type)) {
            $val[':t'] = $type;
            $where[] = 'item.type=:t';
        }
        if (null !== $search) {
            $val[':s'] = '%'.$search.'%';
            $where[] = 'item.title LIKE :s';
        }
        if ($due === true) {
            $where[] = 'item.end < UTC_TIMESTAMP()';
        }
        if ($notgraded === true) {
            $where[] = 'item.is_graded IS FALSE';
        }
        
        $val[':sc'] = $school_id;
        $where[] = 'program.school_id=:sc';
        
        $cw='';
        $nb = count($where);
        for($i=0; $i<$nb;$i++) {
            $cw.=$where[$i] . ((($nb-1)===$i)?' ':'AND ');
        }
        
        $sql.=$cw.' GROUP BY `item`.`id`';
            
        return $this->selectPdo($sql,$val);
    }
    
    /**
     * Get Last parent id.
     *
     * @param int $course
     *
     * @return int
     */
    public function selectLastParentId($course = null, $id = null)
    {
    	if ($course === null && $id = null) {
    		throw new \Exception('Course and id are null');
    	}
    	if ($course === null) {
    		$course = $this->tableGateway->getSql()->select();
    		$course->columns(array('course_id'))->where(array('id' => $id));
    	}
    
    	$select = $this->tableGateway->getSql()->select();
    	$subselect = $this->tableGateway->getSql()->select();
    
    	$subselect->columns(array('parent_id'))
    		->where(array('parent_id IS NOT NULL'))
    		->where(array('course_id' => $course));
    
    	$select->columns(array('id'))
    		->where(array(new NotIn('id', $subselect)))
    		->where(array('course_id' => $course));
    
    	$res = $this->selectWith($select);
    
    	return (($res->count() > 0) ? $res->current()->getId() : null);
    }

    /**
     * @param int $course
     * @param int $user
     */
    public function getListGradeItem($grading_policy = null, $course = null, $user = null, $item_prog = null)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('id', 'title', 'grading_policy_id', 'item$nbr_comment' => new Expression('CAST(SUM(IF(item_assignment_comment.id IS NOT NULL, 1, 0)) AS DECIMAL )')))
            ->join('submission', 'submission.item_id=item.id', array('id'))
            ->join('submission_user', 'submission_user.submission_id=submission.id', array('user_id'))
            ->join('item_assignment_relation', 'item_assignment_relation.submission_id=submission_user.id', array(), $select::JOIN_LEFT)
            ->join('item_assignment', 'item_assignment.id=item_assignment_relation.item_assignment_id', array('item_item_grading$assignmentId' => 'id',
                'item_assignment$submit_date' => new Expression('DATE_FORMAT(item_assignment.submit_date, "%Y-%m-%dT%TZ")'), 'id', ), $select::JOIN_LEFT)
            ->join(array('item_item_grading' => 'item_grading'), 'item_item_grading.item_prog_user_id=item_prog_user.id', array('grade', 'created_date'), $select::JOIN_LEFT)
            ->join('item_assignment_comment', 'item_assignment_comment.item_assignment_id=item_assignment.id', array(), $select::JOIN_LEFT)
            ->where('item_assignment.submit_date IS NOT NULL');
        if (null !== $course) {
            $select->where(array('item.course_id' => $course));
        }
        if (null !== $grading_policy) {
            $select->where(array('item.grading_policy_id' => $grading_policy));
        }
        if (null !== $user) {
            $select->where(array('item_prog_user.user_id' => $user));
        }
        if (null !== $item_prog) {
            $select->where(array('submission.id' => $item_prog))
            ->group('submission_user.user_id');
        } else {
            $select->group('item.id');
        }

        return $this->selectWith($select);
    }
}
