<?php
namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Expression;

class ItemProg extends AbstractMapper
{

    public function getSubmission($user, $id)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->columns(array('id','item_prog$start_date' => new Expression("DATE_FORMAT(start_date, '%Y-%m-%dT%TZ') ")))
            ->join('item_prog_user', 'item_prog_user.item_prog_id=item_prog.id', array())
            ->join('item', 'item.id=item_prog.item_id', array('id','title','describe','type'))
            ->join('module', 'module.id=item.module_id', array('id','title'))
            ->join('course', 'course.id=module.course_id', array('id','title'))
            ->join('program', 'program.id=course.program_id', array('id','name'))
            ->where(array('item_prog.id' => $id))
            ->where(array('item_prog_user.user_id' => $user))
            ->where(array('item.type' => array(\Application\Model\GradingPolicy::GP_CAPSTONE_PROJECT,\Application\Model\GradingPolicy::GP_INDIVIDUAL_ASSIGNEMENT)));
        
        return $this->selectWith($select);
    }

    public function get($id)
    {
        $select = $this->tableGateway->getSql()->select();
    
        $select->columns(array('id',
            'item_prog$start_date' => new Expression("DATE_FORMAT(item_prog.start_date, '%Y-%m-%dT%TZ') "),
            'item_prog$due_date' => new Expression("DATE_FORMAT(item_prog.due_date, '%Y-%m-%dT%TZ') ")
        ))
            ->join('item', 'item.id=item_prog.item_id', array('id','title','type'))
            ->join('course', 'course.id=item.course_id', array('id','title'))
            ->where(array('item_prog.id' => $id));
        
        return $this->selectWith($select);
    }
    
    /**
     * @param integer $user
     * @param integer $item
     * @param string $start
     * @param string $end
     * @param integer $course
     * @param integer $item_prog
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getList($user, $item = null, $start = null, $end = null, $course = null, $item_prog = null)
    {
        $select = $this->tableGateway->getSql()->select();
        
        if (in_array(\Application\Model\Role::ROLE_INSTRUCTOR_STR, $user['roles']) || in_array(\Application\Model\Role::ROLE_ACADEMIC_STR, $user['roles'])) {
            $select->columns(array('id','item_id','item_prog$editable' => new Expression('1'),'item_prog$start_date' => new Expression("DATE_FORMAT(item_prog.start_date, '%Y-%m-%dT%TZ') "),'item_prog$due_date' => new Expression("DATE_FORMAT(item_prog.due_date, '%Y-%m-%dT%TZ') ")));
        } else {
            $select->columns(array('id','item_id','item_prog$start_date' => new Expression("DATE_FORMAT(item_prog.start_date, '%Y-%m-%dT%TZ') "),'item_prog$due_date' => new Expression("DATE_FORMAT(item_prog.due_date, '%Y-%m-%dT%TZ') ")));
        }
        
        $select->join('item', 'item.id = item_prog.item_id', array('id','title','type'))
            ->join('course', 'course.id = item.course_id', array('id','title'))
            ->join('program', 'program.id = course.program_id', array('id', 'name'))
            ->join('module', 'module.id = item.module_id', array('id','title'))
            ->join('grading_policy', 'grading_policy.id = item.grading_policy_id', array('name','type'));
        
        if (in_array(\Application\Model\Role::ROLE_INSTRUCTOR_STR, $user['roles'])) {
            $select->join('course_user_relation', 'course.id = course_user_relation.course_id', array())->where(array('course_user_relation.user_id' => $user['id']));
        } elseif (! in_array(\Application\Model\Role::ROLE_ACADEMIC_STR, $user['roles'])) {
            $select->join('item_prog_user', 'item_prog_user.item_prog_id = item_prog.id', array())->where(array('item_prog_user.user_id' => $user['id']));
        }
        if (null !== $item) {
            $select->where(array('item_prog.item_id ' => $item));
        }
        if (null != $start && null !== $end) {
            $select->where(array('start_date BETWEEN ? AND ? ' => array($start,$end)));
        }
        if (null !== $course) {
            $select->where(array('course.id' => $course));
        }
        if(null !== $item_prog) {
            $select->where(array('item_prog.id' => $item_prog));
        }
        
        return $this->selectWith($select);
    }

    public function getListRecord($item, $user, $is_student = false)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->columns(array('id','item_prog$start_date' => new Expression('DATE_FORMAT(item_prog.start_date, "%Y-%m-%dT%TZ")'),'due_date'))
            ->join('videoconf', 'item_prog.id=videoconf.item_prog_id', array(), $select::JOIN_INNER)
            ->where(array('item_prog.item_id' => $item));
        
        if ($is_student !== false) {
            $select->join('item_prog_user', 'item_prog.id=item_prog_user.item_prog_id', array(), $select::JOIN_INNER)->where(array('item_prog_user.user_id' => $user));
        }
        
        return $this->selectWith($select);
    }

    public function getByItemAssignment($item_assignement)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->columns(array('start_date','id'))
            ->join('item_prog', 'item_prog.item_id=item.id', array())
            ->where(array('item_prog.id' => $item_assignement));
        
        return $this->selectWith($select);
    }
}
