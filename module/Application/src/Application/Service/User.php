<?php
namespace Application\Service;

use Dal\Service\AbstractService;
use DateTimeZone;
use DateTime;
use JRpc\Json\Server\Exception\JrpcException;
use Application\Model\Role as ModelRole;
use Firebase\Token\TokenGenerator;

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
        if (! $result->isValid()) {
            throw new JrpcException($result->getMessages()[0], $result->getCode()['code']);
        }
        
        return $this->getIdentity(true);
    }

    public function _getCacheIdentity($init = false)
    {
        $user = [];
        $id = $this->getServiceAuth()
            ->getIdentity()
            ->getId();
        
        if ($init === false && $this->getCache()->hasItem('identity_' . $id)) {
            $user = $this->getCache()->getItem('identity_' . $id);
        } else {
            $user = $this->getServiceAuth()
                ->getIdentity()
                ->toArray();
            $user['roles'] = [];
            foreach ($this->getServiceRole()->getRoleByUser() as $role) {
                $user['roles'][$role->getId()] = $role->getName();
            }
            $user['school'] = $this->get($id)['school'];
            $secret_key = $this->getServiceLocator()->get('config')['app-conf']['secret_key'];
            $user['wstoken'] = sha1($secret_key . $id);
            $secret_key_fb = $this->getServiceLocator()->get('config')['app-conf']['secret_key_fb'];
            $secret_key_fb_debug = $this->getServiceLocator()->get('config')['app-conf']['secret_key_fb_debug'];
            $generator = new TokenGenerator($secret_key_fb);
            $user['fbtoken'] = $generator->setData(array('uid' => (string) $id))
                ->setOption('debug', $secret_key_fb_debug)
                ->create();
            
            $this->getCache()->setItem('identity_' . $id, $user);
        }
        
        return $user;
    }

    /**
     * @invokable
     *
     * @return \Auth\Authentication\Storage\Model\Identity|false
     */
    public function getIdentity($init = false)
    {
        return $this->_getCacheIdentity($init);
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
     * @param string $gender            
     * @param int $origin            
     * @param int $nationality            
     * @param string $password            
     * @param string $birth_date            
     * @param string $position            
     * @param int $school_id            
     * @param string $interest            
     * @param string $avatar            
     *
     * @throws \Exception
     *
     * @return int
     */
    public function add($firstname, $lastname, $email, $gender = null, $origin = null, $nationality = null, $sis = null, $password = null, $birth_date = null, $position = null, $school_id = null, $interest = null, $avatar = null, $roles = null)
    {
        $m_user = $this->getModel();
        
        $m_user->setFirstname($firstname)
            ->setLastname($lastname)
            ->setEmail($email)
            ->setSis($sis)
            ->setOrigin($origin)
            ->setGender($gender)
            ->setNationality($nationality)
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
            $roles = array(ModelRole::ROLE_STUDENT_STR);
        }
        if (! is_array($roles)) {
            $roles = array($roles);
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
     * @param int $noprogram            
     * @param int $nocourse            
     *
     * @return array
     */
    public function getList($filter = null, $type = null, $level = null, $course = null, $program = null, $search = null, $noprogram = null, $nocourse = null, $schools = null, $order = null, array $exclude = null, $feed = null)
    {
        $mapper = $this->getMapper();
        $res = $mapper->usePaginator($filter)->getList($filter, null, $feed,$this->getServiceAuth()
            ->getIdentity()
            ->getId(), $type, $level, $course, $program, $search, $noprogram, $nocourse, $schools, $order, $exclude);
        
        $res = $res->toArray();
        
        foreach ($res as &$user) {
            $user['roles'] = [];
            $user['program'] = [];
            
            foreach ($this->getServiceRole()->getRoleByUser($user['id']) as $role) {
                $user['roles'][] = $role->getName();
            }
            $user['program'] = $this->getServiceProgram()->getListByUser(null, $user['id'])['list'];
        }
        
        return array('list' => $res,'count' => $mapper->count());
    }

    public function getListOnly($type, $course)
    {
        return $this->getMapper()->getList(null, null, null, $this->getServiceAuth()
            ->getIdentity()
            ->getId(), $type, null, $course, null, null, null, null, false);
    }

    /**
     * @invokable
     *
     * @param array $user            
     * @param array $program            
     */
    public function addProgram($user, $program)
    {
        if (! is_array($user)) {
            $user = array($user);
        }
        if (! is_array($program)) {
            $program = array($program);
        }
        
        return $this->getServiceProgramUserRelation()->add($user, $program);
    }

    /**
     * @invokable
     *
     * @param array $user            
     * @param array $course            
     */
    public function addCourse($user, $course)
    {
        if (! is_array($user)) {
            $user = array($user);
        }
        if (! is_array($course)) {
            $course = array($course);
        }
        
        return $this->getServiceCourseUserRelation()->add($user, $course);
    }

    /**
     * @invokable
     *
     * @param int|array $user            
     * @param int|array $course            
     *
     * @return int
     */
    public function deleteCourse($user, $course)
    {
        return $this->getServiceCourseUserRelation()->deleteCourse($user, $course);
    }

    /**
     * @invokable
     *
     * @param int|array $user            
     * @param int|array $program            
     *
     * @return int
     */
    public function deleteProgram($user, $program)
    {
        return $this->getServiceProgramUserRelation()->deleteProgram($user, $program);
    }

    /**
     * Update User.
     *
     * @invokable
     *
     * @param int $id            
     * @param string $gender            
     * @param int $origin            
     * @param int $nationality            
     * @param string $firstname            
     * @param string $lastname            
     * @param string $email            
     * @param string $birth_date            
     * @param string $position            
     * @param int $school_id            
     * @param string $interest            
     * @param string $avatar            
     * @param array $roles            
     * @param array $programs            
     *
     * @return int
     */
    public function update($id = null, $gender = null, $origin = null, $nationality = null, $firstname = null, $lastname = null, $sis = null, $email = null, $birth_date = null, $position = null, $school_id = null, $interest = null, $avatar = null, $roles = null, $programs = null)
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
            ->setOrigin($origin)
            ->setGender($gender)
            ->setNationality($nationality)
            ->setSis($sis)
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
            ->setPassword(md5($password)), array('id' => $this->getServiceAuth()
            ->getIdentity()
            ->getId(),'password' => md5($oldpassword)));
    }

    /**
     * @invokable
     *
     * @param int $id            
     */
    public function get($id = null)
    {
        $me = $this->getServiceAuth()
            ->getIdentity()
            ->getId();
        
        if ($id === null) {
            $id = $me;
        }
        
        $res_user = $this->getMapper()->get($id, $me);
        if ($res_user->count() <= 0) {
            throw new \Exception('error get user:' . $id);
        }
        
        $user = $res_user->current()->toArray();
        
        $user['roles'] = [];
        $user['program'] = [];
        $user['program'] = $this->getServiceProgram()->getListByUser(null, $user['id'])['list'];
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
        $ret = [];
        if (! is_array($id)) {
            $id = array($id);
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
     * @param int $language_level            
     *
     * @return int
     */
    public function addLanguage($language, $language_level)
    {
        $language_id = $this->getServiceLanguage()->add($language);
        
        return $this->getServiceUserLanguage()->add($language_id, $language_level);
    }

    /**
     * Get user list from item prog.
     *
     * @invokable
     *
     * @param int $item_prog            
     *
     * @return array
     */
    public function getListByItemProg($item_prog)
    {
        return $this->getMapper()->getListByItemProg($item_prog);
    }

    /**
     * Get user list for item_prog and those available.
     *
     * @invokable
     *
     * @param int $item_prog            
     * @param int $item            
     * @param int $course            
     *
     * @return array
     */
    public function getListForItemProg($item_prog, $item, $course)
    {
        return $this->getMapper()->getListForItemProg($item_prog, $item, $course);
    }

    /**
     * Get all students for the instructor.
     *
     * @invokable
     *
     * @return array
     */
    public function getStudentList()
    {
        $ret = [];
        $instructor = $this->getServiceUser()->getIdentity();
        if (in_array(ModelRole::ROLE_INSTRUCTOR_STR, $instructor['roles'])) {
            $ret = $this->getMapper()->getStudentList($instructor['id']);
        }
        
        return $ret;
    }

    /**
     *
     * @param int $conversation            
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListByConversation($conversation)
    {
        return $this->getMapper()->getListByConversation($conversation);
    }

    /**
     * Get user list from item assignment.
     *
     * @invokable
     *
     * @param int $item_assignment            
     *
     * @return array
     */
    public function getListByItemAssignment($item_assignment)
    {
        return $this->getMapper()->getListByItemAssignment($item_assignment);
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
     *
     * @return \Application\Service\Language
     */
    public function getServiceLanguage()
    {
        return $this->getServiceLocator()->get('app_service_language');
    }

    /**
     *
     * @return \Application\Service\Program
     */
    public function getServiceProgram()
    {
        return $this->getServiceLocator()->get('app_service_program');
    }

    /**
     *
     * @return \Application\Service\ProgramUserRelation
     */
    public function getServiceProgramUserRelation()
    {
        return $this->getServiceLocator()->get('app_service_program_user_relation');
    }

    /**
     *
     * @return \Application\Service\CourseUserRelation
     */
    public function getServiceCourseUserRelation()
    {
        return $this->getServiceLocator()->get('app_service_course_user_relation');
    }

    /**
     *
     * @return \Application\Service\UserLanguage
     */
    public function getServiceUserLanguage()
    {
        return $this->getServiceLocator()->get('app_service_user_language');
    }

    /**
     *
     * @return \Zend\Authentication\AuthenticationService
     */
    public function getServiceAuth()
    {
        return $this->getServiceLocator()->get('auth.service');
    }

    /**
     *
     * @return \Application\Service\Role
     */
    public function getServiceRole()
    {
        return $this->getServiceLocator()->get('app_service_role');
    }

    /**
     *
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

    /**
     *
     * @return \Application\Service\User
     */
    public function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }
}
