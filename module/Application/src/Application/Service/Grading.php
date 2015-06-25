<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class Grading extends AbstractService
{
    /**
     * update grading policy.
     *
     * @invokable
     *
     * @param array $datas
     * @param int   $school
     *
     * @return bool
     */
    public function update($datas, $school)
    {
        $this->getMapper()->delete($this->getModel()->setSchoolId($school));
        foreach ($datas as $gp) {
            $this->_add($gp['letter'], $gp['min'], $gp['max'], $gp['grade'], $gp['description'], $school);
        }

        return true;
    }

    /**
     * Get Grading by school id.
     *
     * @invokable
     *
     * @param int $school
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getBySchool($school)
    {
        return $this->getMapper()->select($this->getModel()->setSchoolId($school));
    }

    /**
     * Get Grading by school id.
     *
     * @invokable
     *
     * @param int $school
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getByCourse($course)
    {
        return $this->getMapper()->getByCourse($course);
    }

    public function _add($letter, $min, $max, $grade, $description, $school)
    {
        $m_grading = $this->getModel()
                           ->setLetter($letter)
                           ->setMin($min)
                           ->setMax($max)
                           ->setGrade($grade)
                           ->setDescription($description)
                           ->setSchoolId($school);

        return $this->getMapper()->insert($m_grading);
    }

    public function initTpl($school)
    {
        $res_grading = $this->getMapper()->select($this->getModel()->setTpl(true));

        foreach ($res_grading as $m_grading) {
            $m_grading->setId(null)
                      ->setSchoolId($school)
                      ->setTpl(false);

            $this->getMapper()->insert($m_grading);
        }

        return true;
    }
}
