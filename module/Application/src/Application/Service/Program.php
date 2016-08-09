<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Program
 */
namespace Application\Service;

use Dal\Service\AbstractService;
use Application\Model\Role as ModelRole;
use Zend\Db\Sql\Predicate\IsNull;
use JRpc\Json\Server\Exception\JrpcException;

/**
 * Class Program.
 */
class Program extends AbstractService
{

    /**
     * add program.
     *
     * @invokable
     *
     * @param string $name            
     * @param int $school_id            
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
        if ($this->getServiceUser()->checkOrg($school_id)) {
            // throw new JrpcException('unauthorized orgzanization: ' . $school_id);
        }
        
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
     * @param int $id            
     * @param string $name            
     * @param string $school_id            
     * @param string $level            
     * @param string $sis            
     * @param string $year            
     * @return int
     */
    public function update($id, $name = null, $school_id = null, $level = null, $sis = null, $year = null)
    {
        if ($this->getServiceUser()->checkOrg($school_id)) {
            // throw new JrpcException('unauthorized orgzanization: ' . $school_id);
        }
        
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
     * Get List Program.
     *
     * @invokable
     *
     * @param array $filter            
     * @param string $search            
     * @param int $school            
     * @param bool $self            
     * @param
     *            array exclude
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getList($filter = null, $search = null, $school = null, $self = true, $exclude = null)
    {
        $user = $this->getServiceUser()->getIdentity();
        // @TODO Faire un vrai count
        $res_program = $this->getListByUser($filter, $user['id'], $search, $school, $self, $exclude);
        foreach ($res_program['list'] as $m_program) {
            $m_program->setStudent($this->getServiceUser()
                ->getList(array('n' => 1,'p' => 1), 'student', null, null, $m_program->getId(), null, null, null, false)['count']);
            $m_program->setInstructor($this->getServiceUser()
                ->getList(array('n' => 1,'p' => 1), 'instructor', null, null, $m_program->getId(), null, null, null, false)['count']);
            $m_program->setCourse($this->getServiceCourse()
                ->count($m_program->getId()));
        }
        
        return $res_program;
    }

    /**
     * Get List By User.
     *
     * @param int $user_id            
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListUser($user_id)
    {
        return $this->getMapper()->getListUser($user_id);
    }

    /**
     * Get List By User.
     *
     * @param array $filter            
     * @param int $user_id            
     * @param string $search            
     * @param int $school_id            
     * @param bool $self            
     * @param array $exclude            
     * @return array
     */
    public function getListByUser($filter = null, $user_id = null, $search = null, $school_id = null, $self = true, $exclude = null)
    {
        if ($user_id === null) {
            $identity = $this->getServiceUser()->getIdentity();
            $user_id = $identity['id'];
        } else {
            $identity = $this->getServiceUser()->get($user_id);
        }
        
        $mapper = $this->getMapper();
        // @todo Faire du propre dans les roles une fois que les relations seront ok
        $is_admin_academic = (in_array(ModelRole::ROLE_SADMIN_STR, $identity['roles'])) || (in_array(ModelRole::ROLE_ADMIN_STR, $identity['roles'])) || (in_array(ModelRole::ROLE_ACADEMIC_STR, $identity['roles']));
        
        $res = $mapper->usePaginator($filter)->getList($user_id, $search, $school_id, $is_admin_academic, $self, $exclude);
        
        return ['list' => $res,'count' => $mapper->count()];
    }

    /**
     * Get List By User
     *
     * @param inr $school_id            
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListBySchool($school_id)
    {
        return $this->getMapper()->select($this->getModel()
            ->setSchoolId($school_id)
            ->setDeletedDate(new IsNull()));
    }

    /**
     * Get Program
     *
     * @invokable
     *
     * @param int $id            
     * @return \Application\Model\Program
     */
    public function get($id)
    {
        $res_program = $this->getMapper()->get($id);
        
        if ($res_program->count() <= 0) {
            throw new \Exception('error get program');
        }
        
        $m_program = $res_program->current();
        $m_program->setStudent($this->getServiceUser()
            ->getList(null, 'student', null, null, $m_program->getId()));
        $m_program->setInstructor($this->getServiceUser()
            ->getList(null, 'instructor', null, null, $m_program->getId()));
        $m_program->setCourse($this->getServiceCourse()
            ->getList($m_program->getId()));
        
        return $m_program;
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
        $ret = [];
        if (! is_array($id)) {
            $id = array($id);
        }
        
        foreach ($id as $p) {
            $ret[$p] = $this->getMapper()->delete($this->getModel()
                ->setId($p));
        }
        
        return $ret;
    }

    /**
     * Get Service User.
     *
     * @return \Application\Service\User
     */
    private function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }

    /**
     * Get Service Course.
     *
     * @return \Application\Service\Course
     */
    private function getServiceCourse()
    {
        return $this->getServiceLocator()->get('app_service_course');
    }
}
