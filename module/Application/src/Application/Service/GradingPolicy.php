<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Grading Policy
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class GradingPolicy.
 */
class GradingPolicy extends AbstractService
{
    /**
     * replace grading.
     *
     * @invokable
     *
     * @param array $datas
     * @param int   $course
     *
     * @return bool
     */
    public function replace($datas, $course)
    {
        $this->getMapper()->delete($this->getModel()
            ->setCourseId($course));
        foreach ($datas as $gp) {
            $this->_add($gp['name'], $gp['grade'], $course);
        }

        return true;
    }

    /**
     * add grading.
     *
     * @invokable
     * 
     * @param string $name
     * @param int    $grade
     * @param int    $course_id
     * @param array  $criterias
     *
     * @return int
     */
    public function add($name, $grade, $course_id, $criterias = null)
    {
        $m_grading = $this->getModel()
            ->setName($name)
            ->setGrade($grade)
            ->setCourseId($course_id);

        $this->getMapper()->insert($m_grading);
        $id = $this->getMapper()->getLastInsertValue();
        if (null !== $criterias) {
            $this->getServiceCriteria()->update($criterias, $id);
        }

        return $id;
    }

    /**
     * update grading.
     *
     * @invokable
     *
     * @param int    $id
     * @param string $name
     * @param int    $grade
     * @param array  $criterias
     *
     * @return int
     */
    public function update($id, $name = null, $grade = null, $criterias = null)
    {
        $m_grading = $this->getModel()
            ->setName($name)
            ->setGrade($grade)
            ->setId($id);
        if (null !== $criterias) {
            $this->getServiceCriteria()->update($criterias, $id);
        }

        return $this->getMapper()->update($m_grading);
    }

    /**
     * delete grading policy.
     *
     * @invokable
     *
     * @param int $id
     *
     * @return int
     */
    public function delete($id)
    {
        if (!is_array($id)) {
            $id = array($id);
        }
        $ret = 0;
        foreach ($id as $i) {
            $ret += $this->getMapper()->delete($this->getModel()
                ->setId($i));
        }

        return $ret;
    }

    /**
     * Get Grading Policy By course Id.
     *
     * @todo vérifier coté ui course_id ou course
     * @invokable
     *
     * @param int $course_id
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function get($course)
    {
        $res_grading_policy = $this->getMapper()->select($this->getModel()
            ->setCourseId($course));

        foreach ($res_grading_policy as $m_grading_policy) {
            $m_grading_policy->setCriterias($this->getServiceCriteria()
                ->getList($m_grading_policy->getId()));
        }

        return $res_grading_policy;
    }

    /**
     * Get Grading Policy By submission Id.
     *
     * @invokable
     *
     * @param int $submission_id
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getBySubmission($submission_id)
    {
        $m_grading_policy = $this->getMapper()
            ->getBySubmission($submission_id)
            ->current();
        $m_grading_policy->setCriterias($this->getServiceCriteria()
            ->getList($m_grading_policy->getId()));

        return $m_grading_policy;
    }

    /**
     * Init Template.
     *
     * @param int $course_id
     *
     * @return bool
     */
    public function initTpl($course_id)
    {
        $res_grading_policy = $this->getMapper()->select($this->getModel()
            ->setTpl(true));

        foreach ($res_grading_policy as $m_grading_policy) {
            $m_grading_policy->setId(null)
                ->setCourseId($course_id)
                ->setTpl(false);

            $this->getMapper()->insert($m_grading_policy);
        }

        return true;
    }

    /**
     * Get the list of Grading policy by course id.
     *
     * @invokable
     *
     * @param int $course
     * @param int $user
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListByCourse($course, $user)
    {
        $res_grading_policy = $this->getMapper()->getListByCourse($course, $user);
        foreach ($res_grading_policy as $m_grading_policy) {
            $m_grading_policy->setCriterias($this->getServiceCriteria()
                ->getList($m_grading_policy->getId()));
        }

        return $res_grading_policy;
    }

    /**
     * Get Service Criteria.
     *
     * @return \Application\Service\Criteria
     */
    private function getServiceCriteria()
    {
        return $this->getServiceLocator()->get('app_service_criteria');
    }
}
