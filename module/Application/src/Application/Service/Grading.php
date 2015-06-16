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
     * @param int   $course
     *
     * @return bool
     */
    public function update($datas, $course)
    {
        $this->getMapper()->delete($this->getModel()->setCourseId($course));
        foreach ($datas as $gp) {
            $this->_add($gp['letter'], $gp['min'], $gp['max'], $gp['grade'], $gp['description'], $course);
        }

        return true;
    }

    /**
     * Get Grading by course id.
     *
     * @invokable
     *
     * @param int $id
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function get($id)
    {
        return $this->getMapper()->select($this->getModel()->setCourseId($id));
    }

    /**
     * @param unknown $user
     * @param unknown $program
     * @param unknown $course
     */
    public function getget($user, $program, $course)
    {
    }

    public function _add($letter, $min, $max, $grade, $description, $course)
    {
        $m_grading = $this->getModel()
                           ->setLetter($letter)
                           ->setMin($min)
                           ->setMax($max)
                           ->setGrade($grade)
                           ->setDescription($description)
                           ->setCourseId($course);

        return $this->getMapper()->insert($m_grading);
    }

    public function initTpl($course)
    {
        $res_grading = $this->getMapper()->select($this->getModel()->setTpl(true));

        foreach ($res_grading as $m_grading) {
            $m_grading->setId(null)
                      ->setCourseId($course)
                      ->setTpl(false);

            $this->getMapper()->insert($m_grading);
        }

        return true;
    }
}
