<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Program User Relation
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class ProgramUserRelation.
 */
class ProgramUserRelation extends AbstractService
{
    /**
     * Add Users to Program.
     * 
     * @param array $user
     * @param array $program
     *
     * @return array
     */
    public function add($user, $program)
    {
        $ret = [];
        foreach ($user as $u) {
            foreach ($program as $p) {
                $ret[$u][$p] = $this->getMapper()->insertUserProgram($p, $u);
                /*if($ret[$u][$p] > 0) {
                    $res_course = $this->getServiceCourse()->getListLite($p);
                    foreach ($res_course as $m_course) {
                        $this->getServiceCourseUserRelation()->_add($u, $m_course->getId());
                    }
                }*/
            }
        }

        return $ret;
    }
    
    /**
     * Add Users to Program
     *
     * @param int $user
     * @param int $program
     * @return int
     */
    public function _add($user_id, $program_id)
    {
        return $this->getMapper()->insertUserProgram($program_id, $user_id);
    }
    

    /**
     * Delete User To Program.
     * 
     * @param array $user
     * @param array $program
     *
     * @return array
     */
    public function deleteProgram($user, $program)
    {
        $ret = array();

        if (!is_array($user)) {
            $user = array($user);
        }

        if (!is_array($program)) {
            $program = array($program);
        }

        foreach ($user as $u) {
            foreach ($program as $p) {
                $ret[$u][$p] = $this->getMapper()->delete($this->getModel()->setProgramId($p)->setUserId($u));
                if($ret[$u][$p] > 0) {
                    $res_course = $this->getServiceCourse()->getListLite($p);
                    foreach ($res_course as $m_course) {
                        $this->getServiceCourseUserRelation()->deleteCourse($u, $m_course->getId());
                    }
                }
            }
        }

        return $ret;
    }

    /**
     * Delete user of all program.
     * 
     * @param int $user_id
     *
     * @return int
     */
    public function deleteByUser($user)
    {
        return $this->getMapper()->delete($this->getModel()->setUserId($user));
    }
    
    /**
     * Get Service ProgramUserRelation.
     *
     * @return \Application\Service\CourseUserRelation
     */
    private function getServiceCourseUserRelation()
    {
        return $this->getServiceLocator()->get('app_service_course_user_relation');
    }
    
    /**
     * Get Service Course
     *
     * @return \Application\Service\Course
     */
    private function getServiceCourse()
    {
        return $this->getServiceLocator()->get('app_service_course');
    }
}
