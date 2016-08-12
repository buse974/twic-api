<?php
namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Predicate\NotIn;

class Course extends AbstractMapper
{

    /**
     * Request Course Get
     *
     * @param int $id            
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function get($id)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->columns(array('id','title','abstract','description','picture','objectives','teaching','attendance','duration','video_link','video_token','learning_outcomes','notes'))
            ->join('item', 'item.course_id=course.id', array(), $select::JOIN_LEFT)
            ->join(array('course_user' => 'user'), 'course_user.id=course.creator_id', array('id','firstname','lastname','email'))
            ->join(array('course_user_school' => 'school'), 'course_user_school.id=course_user.school_id', array('id','name','logo'), $select::JOIN_LEFT)
            ->join(array('course_program' => 'program'), 'course_program.id=course.program_id', array('id','name'))
            ->join(array('course_school' => 'school'), 'course_program.school_id=course_school.id', array('id','name','logo'), $select::JOIN_LEFT)
            ->where(array('course.id' => $id))
            ->group('course.id');
        
        return $this->selectWith($select);
    }

    /**
     * Request Course Get List
     *
     * @param int $program_id            
     * @param string $search            
     * @param array $filter            
     * @param int $user_id            
     * @param int $school_id            
     * @param array $exclude            
     * @param string $is_admin_academic            
     * @param string $self            
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getList($program_id = null, $search = null, $filter = null, $user_id = null, $school_id = null, $exclude = null, $is_admin_academic = false, $self = true)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id','title','abstract','description','picture','objectives','teaching','attendance','duration','video_link','video_token','learning_outcomes','notes','program_id'))
            ->join('item', 'item.course_id=course.id', array(), $select::JOIN_LEFT)
            ->join('program', 'program.id=course.program_id', [])
            ->join('school', 'school.id=program.school_id', ['course$school_id' => 'id'])
            ->where(array('course.deleted_date IS NULL'))
            ->where(array('program.deleted_date IS NULL'))
            ->where(array('school.deleted_date IS NULL'))
            ->group('course.id');
        
        if (! empty($program_id)) {
            $select->where(array('course.program_id' => $program_id));
        }
        if (! empty($exclude)) {
            $select->where(new NotIn('course.id', $exclude));
        }
        if (false !== $self) {
            if ($is_admin_academic === true) {
                $select->join('organization_user', 'organization_user.organization_id=school.id', [])->where(['organization_user.user_id' => $user_id]);
            } else {
                $select->join('course_user_relation', 'course_user_relation.course_id=course.id', [])->where(['course_user_relation.user_id' => $user_id]);
            }
        }
        if (null !== $school_id) {
            $select->where(array('program.school_id' => $school_id));
        }
        if (null == ! $search) {
            $select->where(array('course.title LIKE ? ' => '%' . $search . '%'));
        }
        
        return $this->selectWith($select);
    }

    /**
     * Request Course Get Count By Program
     *
     * @param int $program_id            
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getCount($program_id)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->columns(array('course$nbr_course' => new Expression('COUNT(true)')))
            ->join('program', 'program.id=course.program_id', array())
            ->where(array('course.deleted_date IS NULL'))
            ->where(array('course.program_id' => $program_id));
        
        return $this->selectWith($select);
    }
}
