<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Grading
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class Grading.
 */
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
     * update grading policy by program.
     *
     * @invokable
     *
     * @param array $datas
     * @param int   $program
     *
     * @return bool
     */
    public function updateProgram($datas, $program)
    {
        $this->getMapper()->delete($this->getModel()->setProgramId($program));
        foreach ($datas as $gp) {
            $this->_add($gp['letter'], $gp['min'], $gp['max'], $gp['grade'], $gp['description'], null, $program);
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
    public function getBySchool($school = null)
    {
        if (null === $school) {
            $school = $this->getServiceUser()->getIdentity()['school']['id'];
        }

        return $this->getMapper()->select($this->getModel()->setSchoolId($school));
    }

    /**
     * Get Grading by program id.
     *
     * @invokable
     *
     * @param int $program
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getByProgram($program)
    {
        return $this->getMapper()->select($this->getModel()->setProgramId($program));
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

    /**
     * Add Grading.
     *
     * @param int    $letter
     * @param int    $min
     * @param int    $max
     * @param inr    $grade
     * @param string $description
     * @param int    $school_id
     * @param int    $program_id
     *
     * @return int
     */
    public function _add($letter, $min, $max, $grade, $description, $school_id = null, $program_id = null)
    {
        $m_grading = $this->getModel()
                           ->setLetter($letter)
                           ->setMin($min)
                           ->setMax($max)
                           ->setGrade($grade)
                           ->setDescription($description)
                           ->setSchoolId($school_id)
                           ->setProgramId($program_id);

        return $this->getMapper()->insert($m_grading);
    }

    /**
     * Initialise grading with template.
     *
     * @param int $school_id
     *
     * @return bool
     */
    public function initTpl($school_id)
    {
        $res_grading = $this->getMapper()->select($this->getModel()->setTpl(true));

        foreach ($res_grading as $m_grading) {
            $m_grading->setId(null)
                      ->setSchoolId($school_id)
                      ->setTpl(false);

            $this->getMapper()->insert($m_grading);
        }

        return true;
    }

    /**
     * Get Service User.
     *
     * @return \Application\Service\User
     */
    private function getServiceUser()
    {
        return $this->container->get('app_service_user');
    }
}
