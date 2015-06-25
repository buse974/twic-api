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
     * @param int   $course
     *
     * @return bool
     */
    public function replace($datas, $course)
    {
        $this->getMapper()->delete($this->getModel()->setCourseId($course));
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
    public function update($datas)
    {
        $ret = array();
        foreach ($datas as $gp) {
            $name = isset($gp['name']) ? $gp['name'] : null;
            $grade = isset($gp['grade']) ? $gp['grade'] : null;

            $ret[$gp['id']] = $this->_update($gp['id'], $name, $grade);
        }

        return $ret;
    }

    /**
     * @param int    $id
     * @param string $name
     * @param int    $grade
     */
    public function _update($id, $name = null, $grade = null)
    {
        $m_grading = $this->getModel()
                          ->setName($name)
                          ->setGrade($grade)
                          ->setId($id);

        return $this->getMapper()->update($m_grading);
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
        return $this->getMapper()->delete($this->getModel()->setId($id));
    }

    /**
     * add grading.
     *
     * @invokable
     *
     * @param int    $course_id
     * @param string $name
     * @param string $grade
     *
     * @return int
     */
    public function add($course_id, $name = null, $grade = null)
    {
        if ($this->_add($name, $grade, $course_id) <= 0) {
            throw new \Exception('error insert grading policy');
        }

        return $this->getMapper()->getLastInsertValue();
    }

    public function _add($name, $grade, $course)
    {
        $m_grading = $this->getModel()
                          ->setName($name)
                          ->setGrade($grade)
                          ->setCourseId($course);

        return $this->getMapper()->insert($m_grading);
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
        return $this->getMapper()->select($this->getModel()->setCourseId($course));
    }

    public function initTpl($course)
    {
        $res_grading_policy = $this->getMapper()->select($this->getModel()->setTpl(true));

        foreach ($res_grading_policy as $m_grading_policy) {
            $m_grading_policy->setId(null)
                             ->setCourseId($course)
                             ->setTpl(false);

            $this->getMapper()->insert($m_grading_policy);
        }

        return true;
    }
    

    /**
     * Get the list of Grading policy by course id
     * 
     * @param integer $course
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListByCourse($course)
    {
    	return $this->getMapper()->select($this->getModel()->setCourseId($course));
    }
}
