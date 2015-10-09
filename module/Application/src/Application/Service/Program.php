<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class Program extends AbstractService
{
    /**
     * add program.
     *
     * @invokable
     *
     * @param string $name
     * @param int    $school_id
     * @param string $level
     * @param string $sis
     * @param string $year
     *
     * @throws \Exception
     *
     * @return int
     */
    public function add($name, $school_id, $level = null, $sis = null, $year = null)
    {
        $m_program = $this->getModel();

        $m_program->setName($name)
                  ->setSchoolId($school_id)
                  ->setLevel($level)
                  ->setSis($sis)
                  ->setYear($year);

        if ($this->getMapper()->insert($m_program) <= 0) {
            throw new \Exception('error insert');
        }

        return $this->getMapper()->getLastInsertValue();
    }

    /**
     * Update Program.
     *
     * @invokable
     *
     * @param int    $id
     * @param string $name
     * @param string $school_id
     * @param string $level
     * @param string $sis
     * @param string $year
     *
     * @return int
     */
    public function update($id, $name = null, $school_id = null, $level = null, $sis = null, $year = null)
    {
        $m_program = $this->getModel();

        $m_program->setId($id)
                  ->setName($name)
                  ->setSchoolId($school_id)
                  ->setLevel($level)
                  ->setSis($sis)
                  ->setYear($year);

        return $this->getMapper()->update($m_program);
    }

    /**
     * @invokable
     *
     * @param array $filter
     */
    public function getList($filter = null, $search = null)
    {
        $res_program = $this->getListByUser($filter, $this->getServiceAuth()->getIdentity()->getId(), false, $search);

        foreach ($res_program['list'] as $m_program) {
            $m_program->setStudent($this->getServiceUser()->getList(array('n' => 1, 'p' => 1), 'student', null, null, $m_program->getId())['count']);
            $m_program->setInstructor($this->getServiceUser()->getList(array('n' => 1, 'p' => 1), 'instructor', null, null, $m_program->getId())['count']);
            $m_program->setCourse($this->getServiceCourse()->getList($m_program->getId(), null, array('n' => 1, 'p' => 1))['count']);
        }

        return $res_program;
    }

    public function getListBySchool($school)
    {
        return $this->getMapper()->select($this->getModel()->setSchoolId($school));
    }

    /**
     * @invokable
     *
     * @param int $id
     */
    public function get($id)
    {
        $res_program = $this->getMapper()->get($id);

        if ($res_program->count() <= 0) {
            throw new \Exception('error get program');
        }

        $m_program = $res_program->current();
        $m_program->setStudent($this->getServiceUser()->getList(null, 'student', null, null, $m_program->getId()));
        $m_program->setInstructor($this->getServiceUser()->getList(null, 'instructor', null, null, $m_program->getId()));
        $m_program->setCourse($this->getServiceCourse()->getList($m_program->getId()));

        return $m_program;
    }

    public function getListByUser($filter = null, $user = null, $all = false, $search = null)
    {
        if ($user === null) {
            $user = $this->getServiceAuth()->getIdentity()->getId();
        }
        $mapper = $this->getMapper();

        $res = $mapper->usePaginator($filter)->getList($user, $all, $search);

        return array('list' => $res, 'count' => $mapper->count());
    }

    /**
     * Delete Program by ID.
     *
     * @invokable
     *
     * @param int $id
     *
     * @return int
     */
    public function delete($id)
    {
        $ret = array();

        if (!is_array($id)) {
            $id = array($id);
        }

        foreach ($id as $p) {
            $m_program = $this->getModel()->setDeletedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'))->setId($p);
            $ret[$p] = $this->getMapper()->update($m_program);
        }

        return $ret;
    }

    /**
     * @return \Application\Service\User
     */
    public function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }

    /**
     * @return \Application\Service\Course
     */
    public function getServiceCourse()
    {
        return $this->getServiceLocator()->get('app_service_course');
    }

    /**
     * @return \Zend\Authentication\AuthenticationService
     */
    public function getServiceAuth()
    {
        return $this->getServiceLocator()->get('auth.service');
    }
}
