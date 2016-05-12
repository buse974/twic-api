<?php
namespace Application\Service;

use Dal\Service\AbstractService;

class GradingPolicy extends AbstractService
{

    /**
     * replace grading.
     *
     * @invokable
     *
     * @param array $datas            
     * @param int $course            
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
     * update grading policy.
     *
     * @invokable
     *
     * @param array $datas            
     */
    public function update($datas, $course)
    {
        $ret = array();
        $ids = [];
        foreach ($datas as $gp) {
            $name = isset($gp['name']) ? $gp['name'] : null;
            $grade = isset($gp['grade']) ? $gp['grade'] : null;
            $criterias = isset($gp['criterias']) ? $gp['criterias'] : null;
            if (array_key_exists('id', $gp)) {
                $ids[] = $gp['id'];
                $ret[$gp['id']] = $this->_update($gp['id'], $name, $grade, $criterias);
            } else {
                $id = $this->_add($name, $grade, $course, $criterias);
                $ids[] = $id;
                $ret[$id] = null !== $criterias ? $this->getServiceCriteria()->update($criterias, $id) : [];
            }
        }
        $this->getMapper()->deleteNotIn($ids, $course);
        return $ret;
    }

    public function _add($name, $grade, $course, $criterias)
    {
        $m_grading = $this->getModel()
            ->setName($name)
            ->setGrade($grade)
            ->setCourseId($course);
        
        $this->getMapper()->insert($m_grading);
        return $this->getMapper()->getLastInsertValue();
    }
    /**
     *
     * @param int $id            
     * @param string $name            
     * @param int $grade            
     */
    public function _update($id, $name = null, $grade = null, $criterias = null)
    {
        $m_grading = $this->getModel()
            ->setName($name)
            ->setGrade($grade)
            ->setId($id);
        $this->getMapper()->update($m_grading);
        return null !== $criterias ? $this->getServiceCriteria()->update($criterias, $id) : [];
    }

    /**
     * delete grading policy.
     *
     * @invokable
     *
     * @param int $id            
     */
    public function delete($id)
    {
        if (! is_array($id)) {
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
     * add grading.
     *
     * @invokable
     *
     * @param int $course_id            
     * @param string $name            
     * @param string $grade            
     *
     * @return int
     */
    public function add($course_id, $name = null, $grade = null, $criterias)
    {
        if ($this->_add($name, $grade, $course_id) <= 0) {
            throw new \Exception('error insert grading policy');
        }
        
        $this->getServiceCriteria()->update($criterias, $id);
        return $this->getMapper()->getLastInsertValue();
    }


    /**
     * Get Grading Policy By course Id.
     *
     * @invokable
     *
     * @param int $course            
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function get($course)
    {
        $res_grading_policy = $this->getMapper()->select($this->getModel()
            ->setCourseId($course)); 
        
        foreach ($res_grading_policy as $m_grading_policy) {
            $m_grading_policy->setCriterias($this->getServiceCriteria()->getList($m_grading_policy->getId()));
        }
        
        return $res_grading_policy;
    }
    
        /**
     * Get Grading Policy By submission Id.
     *
     * @invokable
     *
     * @param int $submission            
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getBySubmission($submission)
    {
        $m_grading_policy = $this->getMapper()->getBySubmission($submission)->current(); 
        $m_grading_policy->setCriterias($this->getServiceCriteria()->getList($m_grading_policy->getId()));
        
        return $m_grading_policy;
    }

    public function initTpl($course)
    {
        $res_grading_policy = $this->getMapper()->select($this->getModel()
            ->setTpl(true));
        
        foreach ($res_grading_policy as $m_grading_policy) {
            $m_grading_policy->setId(null)
                ->setCourseId($course)
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
     */
    public function getListByCourse($course, $user)
    {
        $res_grading_policy = $this->getMapper()->getListByCourse($course, $user);
        foreach($res_grading_policy as $m_grading_policy){
            $m_grading_policy->setCriterias($this->getServiceCriteria()->getList($m_grading_policy->getId()));
        }
        return $res_grading_policy;
    }

    /**
     *
     * @return \Application\Service\GradingPolicyGrade
     */
    public function getServiceGradingPolicyGrade()
    {
        return $this->getServiceLocator()->get('app_service_grading_policy_grade');
    }
    
    /**
     *
     * @return \Application\Service\Criteria
     */
    public function getServiceCriteria()
    {
        return $this->getServiceLocator()->get('app_service_criteria');
    }
}
