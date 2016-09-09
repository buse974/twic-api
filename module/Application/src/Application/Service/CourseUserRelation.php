<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Course User Relation
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class CourseUserRelation.
 */
class CourseUserRelation extends AbstractService
{
    /**
     * Add relation user and course.
     *
     * @param int|array $user_id
     * @param int|array $course_id
     *
     * @return array
     */
    public function add($user_id, $course_id)
    {
        $ret = [];

        if (!is_array($user_id)) {
            $user_id = array($user_id);
        }
        if (!is_array($course_id)) {
            $course_id = array($course_id);
        }
        foreach ($user_id as $u) {
            foreach ($course_id as $c) {
                $ret[$u][$c] = $this->_add($u, $c);
                if($ret[$u][$c] > 0) {
                    $m_course = $this->getServiceCourse()->getLite($c);
                    $this->getServiceProgramUserRelation()->_add($u, $m_course->getProgramId());
                }
            }
        }

        return $ret;
    }

    /**
     * Add relation user and course
     * 
     * @param int $user_id
     * @param int $course_id
     * @return int
     */
    public function _add($user_id, $course_id)
    {
        return $this->getMapper()->insertUserCourse($course_id, $user_id);
    }
    
    /**
     * Delete relation user and course.
     *
     * @param int|array $user_id
     * @param int|array $course_id
     *
     * @return array
     */
    public function deleteCourse($user_id, $course_id)
    {
        $ret = [];

        if (!is_array($user_id)) {
            $user_id = array($user_id);
        }
        if (!is_array($course_id)) {
            $course_id = array($course_id);
        }

        foreach ($user_id as $u) {
            foreach ($course_id as $c) {
                $ret[$u][$c] = $this->getMapper()->delete($this->getModel()
                    ->setCourseId($c)
                    ->setUserId($u));
            }
        }

        return $ret;
    }

    /**
     * Delete relation by user.
     *
     * @param int $user_id
     * @return int
     */
    public function deleteByUser($user_id)
    {
        return $this->getMapper()->delete($this->getModel()
            ->setUserId($user_id));
    }
    
    /**
     * Get Service ProgramUserRelation.
     *
     * @return \Application\Service\ProgramUserRelation
     */
    private function getServiceProgramUserRelation()
    {
        return $this->container->get('app_service_program_user_relation');
    } 
    
    /**
     * Get Service Course
     *
     * @return \Application\Service\Course
     */
    private function getServiceCourse()
    {
        return $this->container->get('app_service_course');
    }
}
