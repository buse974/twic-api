<?php
/**
 *
 * TheStudnet (http://thestudnet.com)
 *
 * Item
 *
 */
namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\NotIn;
use Zend\Db\Sql\Predicate\IsNull;
use Zend\Db\Sql\Predicate\Predicate;
use Zend\Db\Sql\Predicate\Expression;
use Dal\Db\Sql\Select;
use Application\Model\Role as ModelRole;

/**
 * Class Item
 */
class Item extends AbstractMapper
{

    /**
     * Request Get Itel By Submission
     *
     * @param int $submission_id
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getBySubmission($submission_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id','title','describe','duration','set_id','is_graded','type','course_id','grading_policy_id','parent_id','order_id','has_submission','has_all_student','is_grouped','coefficient','item$start' => new Expression('DATE_FORMAT(item.start, "%Y-%m-%dT%TZ")'),'item$end' => new Expression('DATE_FORMAT(item.end, "%Y-%m-%dT%TZ")'),'item$cut_off' => new Expression('DATE_FORMAT(item.cut_off, "%Y-%m-%dT%TZ")')])
            ->join('submission', 'submission.item_id=item.id', [])
            ->where(array('submission.id' => $submission_id));
        
        return $this->selectWith($select);
    }

    public function getListTmp($user_id, $course_id = null, $parent_id = null, $start = null, $end = null, $type = null, $is_admin_academic = false)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id','title','duration','set_id','is_graded','type','course_id','grading_policy_id','parent_id','order_id','has_submission','has_all_student','is_grouped','is_complete','coefficient','item$start' => new Expression('DATE_FORMAT(item.start, "%Y-%m-%dT%TZ")'),'item$end' => new Expression('DATE_FORMAT(item.end, "%Y-%m-%dT%TZ")'),'item$cut_off' => new Expression('DATE_FORMAT(item.cut_off, "%Y-%m-%dT%TZ")'),'item$is_started' => new Expression('IF(SUM(IF(submission_user.start_date IS NULL, false, true)) > 0,1,0)')])
            ->join('document', 'document.item_id=item.id', [], $select::JOIN_LEFT)
            ->join('library', 'document.library_id=library.id', array('library!id' => 'id','name','type'), $select::JOIN_LEFT)
            ->join('submission', 'submission.item_id=item.id', [], $select::JOIN_LEFT)
            ->join('submission_user', 'submission_user.submission_id=submission.id', [], $select::JOIN_LEFT)
            ->join('course', 'course.id=item.course_id', [])
            ->join('program', 'program.id=course.program_id', [])
            ->where(array('course.deleted_date IS NULL'))
            ->where(array('program.deleted_date IS NULL'));
        
        if (null !== $course_id) {
            $select->where(array('item.course_id' => $course_id));
        }
        
        if (null !== $type) {
            $select->where(array('item.type' => $type));
        }
        if (null !== $start && null !== $end) {
            $select->where(['( item.start BETWEEN ? AND ? ' => [$start,$end]])
                ->where(['item.end BETWEEN ? AND ?  ' => [$start,$end]], Predicate::OP_OR)
                ->where(['( item.start < ? AND item.end > ? ) ) ' => [$start,$end]], Predicate::OP_OR);
        }
        if ($is_admin_academic === true) {
            $select->join('organization_user', 'organization_user.organization_id=program.school_id', [])->where(['organization_user.user_id' => $user_id]);
        } else {
            $select->join('course_user_relation', 'course_user_relation.course_id=course.id', [])->where(['course_user_relation.user_id' => $user_id]);
        }
        
        $select->group('item.id');
        
        return $this->selectWith($select);
    }

    public function getList($user_id, $course_id = null, $parent_id = null, $start = null, $end = null, $type = null, $is_admin_academic = false)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id','title','duration','set_id','is_graded','type','course_id','grading_policy_id','parent_id','order_id','has_submission','has_all_student','is_grouped','is_complete','coefficient','item$start' => new Expression('DATE_FORMAT(item.start, "%Y-%m-%dT%TZ")'),'item$end' => new Expression('DATE_FORMAT(item.end, "%Y-%m-%dT%TZ")'),'item$cut_off' => new Expression('DATE_FORMAT(item.cut_off, "%Y-%m-%dT%TZ")'),'item$is_started' => new Expression('IF(SUM(IF(submission_user.start_date IS NULL, false, true)) > 0,1,0)')])
            ->join('document', 'document.item_id=item.id', [], $select::JOIN_LEFT)
            ->join('library', 'document.library_id=library.id', array('library!id' => 'id','name','type'), $select::JOIN_LEFT)
            ->join('submission', 'submission.item_id=item.id', [], $select::JOIN_LEFT)
            ->join('submission_user', 'submission_user.submission_id=submission.id', [], $select::JOIN_LEFT)
            ->join('course', 'course.id=item.course_id', [])
            ->join('program', 'program.id=course.program_id', [])
            ->where(array('course.deleted_date IS NULL'))
            ->where(array('program.deleted_date IS NULL'));
        
        if (null !== $course_id) {
            $select->where(array('item.course_id' => $course_id));
        }
        if (null === $start && null === $end) {
            if ($parent_id === 0) {
                $select->where(array('item.parent_id IS NULL'));
            } elseif (null !== $parent_id) {
                $select->where(array('item.parent_id' => $parent_id));
            }
        }
        if (null !== $type) {
            $select->where(array('item.type' => $type));
        }
        if (null !== $start && null !== $end) {
            $select->where(['( item.start BETWEEN ? AND ? ' => [$start,$end]])
                ->where(['item.end BETWEEN ? AND ?  ' => [$start,$end]], Predicate::OP_OR)
                ->where(['( item.start < ? AND item.end > ? ) ) ' => [$start,$end]], Predicate::OP_OR);
        }
        if ($is_admin_academic === true) {
            $select->join('organization_user', 'organization_user.organization_id=program.school_id', [])->where(['organization_user.user_id' => $user_id]);
        } else {
            $select->join('course_user_relation', 'course_user_relation.course_id=course.id', [])
                ->where(['course.is_published IS TRUE'])
                ->where(['course_user_relation.user_id' => $user_id]);
        }
        
        $select->group('item.id');
        
        return $this->selectWith($select);
    }

    /**
     *
     * @param array $me
     */
    public function getListForCalendar($me, $start = null, $end = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id','title','type','course_id','grading_policy_id','parent_id','order_id','has_submission','set_id','item$start' => new Expression('DATE_FORMAT(item.start, "%Y-%m-%dT%TZ")'),'item$end' => new Expression('DATE_FORMAT(item.end, "%Y-%m-%dT%TZ")'),'item$cut_off' => new Expression('DATE_FORMAT(item.cut_off, "%Y-%m-%dT%TZ")')])
            ->join('course', 'course.id=item.course_id', ['id','title'])
            ->join('program', 'program.id=course.program_id', ['id','name'])
            ->where(array('program.school_id' => $me['school']['id']))
            ->where('item.title IS NOT NULL')
            ->where('item.start IS NOT NULL');
        if (! array_key_exists(ModelRole::ROLE_ACADEMIC_ID, $me['roles'])) {
            $select->join('course_user_relation', 'course.id = course_user_relation.course_id', [])->where(array('course_user_relation.user_id' => $me['id']));
        }
        
        if (array_key_exists(ModelRole::ROLE_STUDENT_ID, $me['roles'])) {
            $select->join('set', 'set.id = item.set_id', [], $select::JOIN_LEFT)
                ->join('set_group', 'set.id = set_group.set_id', [], $select::JOIN_LEFT)
                ->join('group', 'group.id = set_group.group_id', [], $select::JOIN_LEFT)
                ->join('group_user', 'group.id = group_user.group_id', [], $select::JOIN_LEFT)
                ->where('( item.set_id IS NULL ')
                ->where(['group_user.user_id = ? )' => $me['id']], Predicate::OP_OR);
        }
        if (null != $start && null !== $end) {
            $select->where(['( item.start BETWEEN ? AND ? ' => [$start,$end]])
                ->where(['item.end BETWEEN ? AND ?  ' => [$start,$end]], Predicate::OP_OR)
                ->where(['( item.start < ? AND item.end > ? ) ) ' => [$start,$end]], Predicate::OP_OR);
        }
        
        return $this->selectWith($select);
    }

    public function get($id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id','title','type','course_id','grading_policy_id','parent_id','order_id','has_submission','has_all_student','is_grouped','coefficient','item$start' => new Expression('DATE_FORMAT(item.start, "%Y-%m-%dT%TZ")'),'item$end' => new Expression('DATE_FORMAT(item.end, "%Y-%m-%dT%TZ")'),'item$cut_off' => new Expression('DATE_FORMAT(item.cut_off, "%Y-%m-%dT%TZ")')])
            ->join('course', 'course.id=item.course_id', array('id','title'))
            ->join('program', 'program.id=course.program_id', array('id','name'))
            ->where(array('item.id' => $id));
        
        return $this->selectWith($select);
    }

    public function getAllow($id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id','title','describe','duration','type','course_id','grading_policy_id','set_id','parent_id','order_id','has_submission','has_all_student','is_grouped','coefficient','item$start' => new Expression('DATE_FORMAT(item.start, "%Y-%m-%dT%TZ")'),'item$end' => new Expression('DATE_FORMAT(item.end, "%Y-%m-%dT%TZ")'),'item$cut_off' => new Expression('DATE_FORMAT(item.cut_off, "%Y-%m-%dT%TZ")')])
            ->join('course', 'course.id=item.course_id', array('id','title'))
            ->join('program', 'program.id=course.program_id', array('id','name'))
            ->join('opt_grading', 'item.id=opt_grading.item_id', array('mode','has_pg','pg_nb','pg_auto','pg_due_date','pg_can_view','user_can_view','pg_stars'), $select::JOIN_LEFT)
            ->where(array('item.id' => $id));
        
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
        if ($parent === null) {
            $subselect->where(array('parent_id IS NULL'));
        } else {
            $subselect->where(array('parent_id' => $parent));
        }
        
        $select->columns(array('id'))
            ->where(array(new NotIn('id', $subselect)))
            ->where(array('course_id' => $course));
        
        if ($parent === null) {
            $select->where(array('parent_id IS NULL'));
        } else {
            $select->where(array('parent_id' => $parent));
        }
        
        $res = $this->selectWith($select);
        
        return ($res->count() > 0) ? $res->current()->getId() : null;
    }

    /**
     * Request Get List Item Submission
     *
     * @param int $user_id
     * @param string $type
     * @param int $program
     * @param int $course
     * @param string $due
     * @param bool $notgraded
     * @param string $search
     * @param bool $is_academic
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListSubmissions($user_id, $type = null, $program = null, $course = null, $due = null, $notgraded = null, $search = null, $is_academic = true)
    {
        $sql = 'SELECT 
                    COUNT(1) AS `item$due`,
                    `item`.`id` AS `item$id`,
                    `item`.`title` AS `item$title`,
                    `item`.`type` AS `item$type`,
                    `item`.`is_grouped`,
                    DATE_FORMAT(item.start, "%Y-%m-%dT%TZ") AS `item$start`,
                    DATE_FORMAT(item.cut_off, "%Y-%m-%dT%TZ") AS `item$cut_off`,
                    DATE_FORMAT(item.end, "%Y-%m-%dT%TZ") AS `item$end`,
                    SUM(IF(submission.is_graded IS NULL, 0, submission.is_graded)) AS `item$graded`,
                    SUM(IF(submission.submit_date IS NULL, 0, 1)) AS `item$submitted`,
                    `course`.`title` AS `course$title`,
                    `program`.`name` AS `program$name`
                FROM 
                    `item`
                        LEFT JOIN 
                    `submission` ON `submission`.`item_id` = `item`.`id` 
                        INNER JOIN 
                    `course` ON `item`.`course_id` = `course`.`id` 
                        INNER JOIN 
                    `program` ON `program`.`id` = `course`.`program_id` ';
        
        $sql .= ($is_academic) ?
                ' INNER JOIN `organization_user` ON `organization_user`.`organization_id`=`program`.`school_id` ' :
                ' INNER JOIN `course_user_relation` ON `course_user_relation`.`course_id`=`course`.`id` ';
        
        $sql .= ' WHERE ';
        
        $where = [];
        $val = [];
        if (! empty($course)) {
            $i = 0;
            $s = [];
            if (! is_array($course)) {
                $course = [$course];
            }
            foreach ($course as $c) {
                ++ $i;
                $val[':c' . $i] = $c;
                $s[] = ':c' . $i;
            }
            $where[] = 'course.id IN (' . implode(',', $s) . ')';
        }
        if (! empty($program)) {
            $i = 0;
            $s = [];
            if (! is_array($program)) {
                $program = [$program];
            }
            foreach ($program as $p) {
                ++ $i;
                $val[':p' . $i] = $p;
                $s[] = ':p' . $i;
            }
            $where[] = 'program.id IN (' . implode(',', $s) . ')';
        }
        if (! empty($type)) {
            $i = 0;
            $s = [];
            if (! is_array($type)) {
                $type = [$type];
            }
            
            foreach ($type as $t) {
                ++ $i;
                $val[':t' . $i] = $t;
                $s[] = ':t' . $i;
            }
            if (in_array('A', $type)) {
                $where[] = "( item.type IN (" . implode(',', $s) . ") OR ( item.is_grouped IS FALSE AND item.type = 'IA'))";
            } elseif (in_array('GA', $type)) {
                $where[] = "( item.type IN (" . implode(',', $s) . ") OR ( item.is_grouped IS TRUE AND item.type = 'IA'))";
            } else {
                $where[] = "item.type IN (" . implode(',', $s) . ")";
            }
        } else {
            $where[] = "item.type IN ('CP', 'IA', 'POLL', 'DISC', 'CHAT')";
        }
        if (null !== $search) {
            $val[':s'] = '%' . $search . '%';
            $where[] = 'item.title LIKE :s';
        }
        if ($due === true) {
            $where[] = 'item.end < UTC_TIMESTAMP()';
        }
        if ($notgraded === true) {
            $where[] = 'item.is_graded IS FALSE';
        }
        
        $val[':sc'] = $user_id;
        
        $where[] = ($is_academic) ?
            'organization_user.user_id=:sc':
            'course_user_relation.user_id=:sc';
        
        $where[] = 'item.updated_date IS NOT NULL';
        $where[] = 'course.deleted_date IS NULL';
        $where[] = 'program.deleted_date IS NULL';
        $where[] = 'item.is_complete IS TRUE';
        
        $cw = '';
        $nb = count($where);
        for ($i = 0; $i < $nb; ++ $i) {
            $cw .= $where[$i] . ((($nb - 1) === $i) ? ' ' : ' AND ');
        }
        
        $sql .= $cw . ' GROUP BY `item`.`id`';
        
        return $this->selectPdo($sql, $val);
    }

    /**
     * Get Last parent id
     *
     * @param int $course_id
     * @param int $id
     * @return int
     */
    public function selectLastParentId($course_id = null, $id = null)
    {
        if ($course_id === null && $id = null) {
            throw new \Exception('Course and id are null');
        }
        if ($course_id === null) {
            $course_id = $this->tableGateway->getSql()->select();
            $course_id->columns(array('course_id'))->where(array('id' => $id));
        }
        
        $select = $this->tableGateway->getSql()->select();
        $subselect = $this->tableGateway->getSql()->select();
        
        $subselect->columns(array('parent_id'))
            ->where(array('parent_id IS NOT NULL'))
            ->where(array('course_id' => $course_id));
        
        $select->columns(array('id'))
            ->where(array(new NotIn('id', $subselect)))
            ->where(array('course_id' => $course_id));
        
        $res = $this->selectWith($select);
        
        return ($res->count() > 0) ? $res->current()->getId() : null;
    }

    /**
     * Request Get List Item Grade
     *
     * @param int $grading_policy_id
     * @param int $course_id
     * @param int $user_id
     * @param int $submission_id
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListGradeItem($grading_policy_id = null, $course_id = null, $user_id = null, $submission_id = null)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->columns(array('id','title','grading_policy_id','item$nbr_comment' => new Expression('SUM(IF(submission_comments.id IS NOT NULL, 1, 0)  )')))
            ->join('submission', 'submission.item_id=item.id', array('id'))
            ->join('submission_user', 'submission_user.submission_id=submission.id', array('grade','user_id','submission_id'))
            ->join('submission_comments', 'submission_comments.submission_id=submission.id', array(), $select::JOIN_LEFT)
            ->where('submission.submit_date IS NOT NULL');
        if (null !== $course_id) {
            $select->where(array('item.course_id' => $course_id));
        }
        if (null !== $grading_policy_id) {
            $select->where(array('item.grading_policy_id' => $grading_policy_id));
        }
        if (null !== $user_id) {
            $select->where(array('submission_user.user_id' => $user_id));
        }
        if (null !== $submission_id) {
            $select->where(array('submission.id' => $submission_id))->group('submission_user.user_id');
        } else {
            $select->group('item.id');
        }
        
        return $this->selectWith($select);
    }

    public function cancelSort($id, $order_id)
    {
        $update = $this->tableGateway->getSql()->update();
        $update->set(['order_id' => $id]);
        if ($order_id instanceof IsNull) {
            $update->where(['order_id IS NULL']);
        } else {
            $update->where(['order_id' => $order_id]);
        }
        
        $update->where(['id <> ?' => $id]);
        
        return $this->updateWith($update);
    }
}
