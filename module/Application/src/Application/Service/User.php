<?php

namespace Application\Service;

use Dal\Service\AbstractService;
use DateTimeZone;
use DateTime;
use JRpc\Json\Server\Exception\JrpcException;
use Application\Model\Role as ModelRole;

class User extends AbstractService
{
    /**
     * Log user.
     *
     * @invokable
     *
     * @param string $user
     * @param string $password
     */
    public function login($user, $password)
    {
        $auth = $this->getServiceAuth();
        $auth->getAdapter()->setIdentity($user);
        $auth->getAdapter()->setCredential($password);

        $result = $auth->authenticate();

        if (!$result->isValid()) {
            throw new JrpcException($result->getMessages()[0], $result->getCode()['code']);
        }

        $user = $result->getIdentity()->toArray();

        $user['roles'] = array();
        foreach ($this->getServiceRole()->getRoleByUser() as $role) {
            $user['roles'][] = $role->getName();
        }

        return $user;
    }

    public function getCacheIdentity()
    {
        $user = array();
        $id = $this->getServiceAuth()
            ->getIdentity()
            ->getId();

        if ($this->getCache()->hasItem('identity_'.$id)) {
            $user = $this->getCache()->getItem('identity_'.$id);
        } else {
            $user['roles'] = array();
            foreach ($this->getServiceRole()->getRoleByUser() as $role) {
                $user['roles'][] = $role->getName();
            }
            $this->getCache()->setItem('identity_'.$id, $user);
        }

        return $user;
    }

    /**
     * @invokable
     *
     * @return \Auth\Authentication\Storage\Model\Identity|false
     */
    public function getIdentity()
    {
        return $this->getServiceAuth()->getIdentity();
    }

    /**
     * @invokable
     */
    public function getListSession()
    {
        $auth = $this->getServiceAuth();

        return $auth->getStorage()->getListSession($auth->getIdentity()
            ->getId());
    }

    /**
     * @invokable
     *
     * @return true
     */
    public function logout()
    {
        $this->getServiceAuth()->clearIdentity();

        return true;
    }

    /**
     * Add User.
     *
     * @invokable
     *
     * @param string $firstname
     * @param string $lastname
     * @param string $email
     * @param string $password
     * @param string $birth_date
     * @param string $position
     * @param int    $school_id
     * @param string $interest
     * @param string $avatar
     *
     * @throws \Exception
     *
     * @return int
     */
    public function add($firstname, $lastname, $email, $password = null, $birth_date = null, $position = null, $school_id = null, $interest = null, $avatar = null, $roles = null)
    {
        $m_user = $this->getModel();

        $m_user->setFirstname($firstname)
            ->setLastname($lastname)
            ->setEmail($email)
            ->setPassword(md5($password))
            ->setBirthDate($birth_date)
            ->setPosition($position)
            ->setSchoolId($school_id)
            ->setInterest($interest)
            ->setAvatar($avatar);

        /*
         * @TODO schoolid vérifier que si il n'est pas admin le school id est automatiquement celui de la personne qui add le user.
         */
        if ($school_id === null) {
            $user = $this->get();
            $m_user->setSchoolId($user['school_id']);
        }

        if ($password !== null) {
            $m_user->setPassword(md5($password));
        }

        if ($this->getMapper()->insert($m_user) <= 0) {
            throw new \Exception('error insert');
        }

        $id = $this->getMapper()->getLastInsertValue();

        if ($roles === null) {
            $roles = array(
                ModelRole::ROLE_STUDENT_STR,
            );
        }
        if (!is_array($roles)) {
            $roles = array(
                $roles,
            );
        }

        foreach ($roles as $r) {
            $this->getServiceUserRole()->add($this->getServiceRole()
                ->getIdByName($r), $id);
        }

        return $id;
    }

    /**
     * @invokable
     *
     * @param string $filter
     * @param string $type
     * @param string $level
     * @param string $course
     * @param string $program
     * @param string $search
     *
     * @return array
     */
    public function getList($filter = null, $type = null, $level = null, $course = null, $program = null, $search = null, $nopragram = null)
    {
        $mapper = $this->getMapper();
        $res = $mapper->usePaginator($filter)->getList($filter, null, $this->getServiceAuth()
            ->getIdentity()
            ->getId(), $type, $level, $course, $program, $search, $nopragram);

        $res = $res->toArray();

        foreach ($res as &$user) {
            $user['roles'] = array();
            $user['program'] = array();

            foreach ($this->getServiceRole()->getRoleByUser($user['id']) as $role) {
                $user['roles'][] = $role->getName();
            }
            $user['program'] = $this->getServiceProgram()->getListByUser(null, $user['id'])['list'];
        }

        return array(
            'list' => $res,
            'count' => $mapper->count(),
        );
    }
    
    public function getListOnly($course)
    {
    	return $this->getMapper()->getList(null, null, null, null, null, $course);
    }

    /**
     * @invokable
     *
     * @param array $user
     * @param array $program
     */
    public function addProgram($user, $program)
    {
        if (!is_array($user)) {
            $user = array(
                $user,
            );
        }

        if (!is_array($program)) {
            $program = array(
                $program,
            );
        }

        return $this->getServiceProgramUserRelation()->add($user, $program);
    }

    /**
     * @invokable
     *
     * @param int|array $user
     * @param int|array $program
     *
     * @return integer
     */
    public function deleteProgram($user, $program)
    {
    	return $this->getServiceProgramUserRelation()->deleteProgram($user, $program);
    }

    /**
     * Update User
     *
     * @invokable
     *
     * @param int    $id
     * @param string $firstname
     * @param string $lastname
     * @param string $email
     * @param string $birth_date
     * @param string $position
     * @param int    $school_id
     * @param string $interest
     * @param string $avatar
     * @param array  $roles
     * @param array  $programs
     *
     * @return int
     */
    public function update($id = null, $firstname = null, $lastname = null, $email = null, $birth_date = null, $position = null, $school_id = null, $interest = null, $avatar = null, $roles = null, $programs = null)
    {
        $m_user = $this->getModel();

        if ($id === null) {
            $id = $this->getServiceAuth()
                ->getIdentity()
                ->getId();
        }

        $m_user->setId($id)
            ->setFirstname($firstname)
            ->setLastname($lastname)
            ->setEmail($email)
            ->setBirthDate($birth_date)
            ->setPosition($position)
            ->setSchoolId($school_id)
            ->setInterest($interest)
            ->setAvatar($avatar);

        if ($roles !== null) {
            foreach ($roles as $r) {
                $this->getServiceUserRole()->deleteByUser($id);
                $this->getServiceUserRole()->add($this->getServiceRole()
                    ->getIdByName($r), $id);
            }
        }

        if ($programs !== null) {
            $this->getServiceProgramUserRelation()->deleteByUser($id);
            $this->addProgram($id, $programs);
        }

        return $this->getMapper()->update($m_user);
    }

    /**
     * @invokable
     *
     * @param string $oldpassword
     * @param string $password
     *
     * @return int
     */
    public function updatePassword($oldpassword, $password)
    {
        return $this->getMapper()->update($this->getModel()
            ->setPassword(md5($password)), array(
            'id' => $this->getServiceAuth()
                ->getIdentity()
                ->getId(),
            'password' => md5($oldpassword),
        ));
    }

    /**
     * @invokable
     *
     * @param int $id
     */
    public function get($id = null)
    {
        if ($id === null) {
            $id = $this->getServiceAuth()->getIdentity()->getId();
        }

        $res_user = $this->getMapper()->get($id);
        if ($res_user->count() <= 0) {
            throw new \Exception('error get user:'.$id);
        }

        $user = $res_user->current()->toArray();

        $user['roles'] = array();
        foreach ($this->getServiceRole()->getRoleByUser($id) as $role) {
            $user['roles'][] = $role->getName();
        }

        return $user;
    }

    /**
     * Delete User.
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
            $id = array(
                $id,
            );
        }

        foreach ($id as $i) {
            $m_user = $this->getModel();
            $m_user->setId($i)->setDeletedDate((new DateTime('now', new DateTimeZone('UTC')))->format('Y-m-d H:i:s'));

            $ret[$i] = $this->getMapper()->update($m_user);
        }

        return $ret;
    }

    /**
     * Add language to user.
     *
     * @invokable
     *
     * @param array $language
     * @param int   $language_level
     *
     * @return int
     */
    public function addLanguage($language, $language_level)
    {
        $language_id = $this->getServiceLanguage()->add($language);

        return $this->getServiceUserLanguage()->add($language_id, $language_level);
    }

    /**
     * delete language to user.
     *
     * @invokable
     *
     * @param int $id
     *
     * @return int
     */
    public function deleteLanguage($id)
    {
        return $this->getServiceUserLanguage()->delete($id);
    }

    /**
     * @return \Application\Service\Language
     */
    public function getServiceLanguage()
    {
        return $this->getServiceLocator()->get('app_service_language');
    }

    /**
     * @return \Application\Service\Program
     */
    public function getServiceProgram()
    {
        return $this->getServiceLocator()->get('app_service_program');
    }

    /**
     * @return \Application\Service\ProgramUserRelation
     */
    public function getServiceProgramUserRelation()
    {
        return $this->getServiceLocator()->get('app_service_program_user_relation');
    }

    /**
     * @return \Application\Service\UserLanguage
     */
    public function getServiceUserLanguage()
    {
        return $this->getServiceLocator()->get('app_service_user_language');
    }

    /**
     * @return \Zend\Authentication\AuthenticationService
     */
    public function getServiceAuth()
    {
        return $this->getServiceLocator()->get('auth.service');
    }

    /**
     * @return \Application\Service\Role
     */
    public function getServiceRole()
    {
        return $this->getServiceLocator()->get('app_service_role');
    }

    /**
     * @return \Application\Service\UserRole
     */
    public function getServiceUserRole()
    {
        return $this->getServiceLocator()->get('app_service_user_role');
    }

    /**
     * Get Storage if define in config.
     *
     * @return \Zend\Cache\Storage\StorageInterface
     */
    public function getCache()
    {
        $config = $this->getServiceLocator()->get('config')['app-conf'];

        return $this->getServiceLocator()->get($config['cache']);
    }
}
