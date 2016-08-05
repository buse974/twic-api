<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * User
 */
namespace Application\Service;

use Dal\Service\AbstractService;
use DateTimeZone;
use DateTime;
use JRpc\Json\Server\Exception\JrpcException;
use Application\Model\Role as ModelRole;
use Firebase\Token\TokenGenerator;
use Zend\Db\Sql\Predicate\IsNull;
use Application\Model\Item as ModelItem;
use Auth\Authentication\Adapter\Model\Identity;

/**
 * Class User.
 */
class User extends AbstractService
{

    /**
     * Log user
     *
     * @invokable
     *
     * @param string $user            
     * @param string $password            
     * @throws JrpcException
     * @return array
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

    /**
     * Get List User By Group.
     *
     * @param int $group_id            
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListUsersByGroup($group_id)
    {
        return $this->getMapper()->getListUsersByGroup($group_id);
    }

    /**
     * Get/Create Identity in cache.
     *
     * @param bool $init            
     *
     * @return array
     */
    public function _getCacheIdentity($init = false)
    {
        $user = [];
        $identity = $this->getServiceAuth()->getIdentity();
        if ($identity === null) {
            return;
        }
        
        $id = $identity->getId();
        if ($init === false && $this->getCache()->hasItem('identity_' . $id)) {
            $user = $this->getCache()->getItem('identity_' . $id);
        } else {
            $user = $identity->toArray();
            
            $user['roles'] = [];
            foreach ($this->getServiceRole()->getRoleByUser() as $role) {
                $user['roles'][$role->getId()] = $role->getName();
            }
            
            $res_user = $this->getMapper()->get($id, $id);
            $user['school'] = ($res_user->count() > 0) ? $res_user->current()->toArray()['school'] : null;
            $user['organizations'] = $this->getServiceOrganization()->_getList($id)->toArray();
            $user['organization_id'] = $user['school']['id'];
            $secret_key = $this->getServiceLocator()->get('config')['app-conf']['secret_key'];
            $user['wstoken'] = sha1($secret_key . $id);
            
            $secret_key_fb = $this->getServiceLocator()->get('config')['app-conf']['secret_key_fb'];
            $secret_key_fb_debug = $this->getServiceLocator()->get('config')['app-conf']['secret_key_fb_debug'];
            
            $generator = new TokenGenerator($secret_key_fb);
            $user['fbtoken'] = $generator->setData(array('uid' => (string) $id))
                ->setOption('debug', $secret_key_fb_debug)
                ->setOption('expires', 1506096687)
                ->create();
            
            $this->getCache()->setItem('identity_' . $id, $user);
        }
        
        return $user;
    }

    /**
     * Delete Cached Identity of user.
     *
     * @param int $id            
     *
     * @return bool
     */
    private function deleteCachedIdentityOfUser($id)
    {
        return $this->getCache()->removeItem('identity_' . $id);
    }

    /**
     * Get/Create Identity External in cache.
     *
     * @param bool $init            
     *
     * @return array
     */
    public function _getCacheIdentityExternal($init = false)
    {
        $user = [];
        $identity = $this->getServiceAuth()->getIdentity();
        if ($identity === null) {
            return;
        }
        $id = $identity->getId();
        
        if ($init === false && $this->getCache()->hasItem('identity_' . $id)) {
            $user = $this->getCache()->getItem('identity_' . $id);
        } else {
            $user = $identity->toArray();
            $user['roles'] = [];
            foreach ($this->getServiceRole()->getRoleByUser() as $role) {
                $user['roles'][$role->getId()] = $role->getName();
            }
            $res_user = $this->getMapper()->get($id, $id);
            $user['school'] = ($res_user->count() > 0) ? $res_user->current()->toArray()['school'] : null;
            $this->getCache()->setItem('identity_' . $id, $user);
        }
        
        return $user;
    }

    /**
     * Get Identity.
     *
     * @invokable
     *
     * @param bool $init            
     * @param bool $external            
     *
     * @return array
     */
    public function getIdentity($init = false, $external = false)
    {
        return ($external) ? $this->_getCacheIdentityExternal($init) : $this->_getCacheIdentity($init);
    }

    /**
     * Get List Session Active.
     *
     * @invokable
     *
     * @return array
     */
    public function getListSession()
    {
        $auth = $this->getServiceAuth();
        
        return $auth->getStorage()->getListSession($auth->getIdentity()
            ->getId());
    }

    /**
     * Log out.
     *
     * @invokable
     *
     * @return bool
     */
    public function logout()
    {
        $this->getServiceAuth()->clearIdentity();
        
        return true;
    }

    /**
     * Add User
     *
     * @invokable
     *
     * @param string $firstname            
     * @param string $lastname            
     * @param string $email            
     * @param string $gender            
     * @param string $origin            
     * @param string $nationality            
     * @param string $sis            
     * @param string $password            
     * @param string $birth_date            
     * @param string $position            
     * @param int $school_id            
     * @param string $interest            
     * @param string $avatar            
     * @param array $roles            
     * @param string $timezone            
     * @param string $background            
     * @param string $nickname            
     *
     * @return int
     */
    public function add($firstname, $lastname, $email, $gender = null, $origin = null, $nationality = null, $sis = null, $password = null, $birth_date = null, $position = null, $school_id = null, $interest = null, $avatar = null, $roles = null, $timezone = null, $background = null, $nickname = null)
    {
        if ($this->getNbrEmailUnique($email) > 0) {
            throw new JrpcException('duplicate email', - 38001);
        }
        
        if (! empty($sis)) {
            if ($this->getNbrSisUnique($sis) > 0) {
                throw new JrpcException('uid email', - 38002);
            }
        }
        
        $m_user = $this->getModel();
        $m_user->setFirstname($firstname)
            ->setLastname($lastname)
            ->setEmail($email)
            ->setSis($sis)
            ->setOrigin($origin)
            ->setGender($gender)
            ->setNationality($nationality)
            ->setBirthDate($birth_date)
            ->setPosition($position)
            ->setInterest($interest)
            ->setAvatar($avatar)
            ->setTimezone($timezone)
            ->setBackground($background)
            ->setNickname($nickname)
            ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
        /*
         * schoolid vérifier que si il n'est pas admin le school id est
         * automatiquement celui de la personne qui add le user.
         */
        if (! in_array(ModelRole::ROLE_SADMIN_STR, $this->getIdentity()['roles'])) {
            $user = $this->get();
            $school_id = $user['school_id'];
        }
        
        if (empty($password)) {
            $cars = 'azertyiopqsdfghjklmwxcvbn0123456789/*.!:;,....';
            $long = strlen($cars);
            srand((double) microtime() * 1000000);
            $password = '';
            for ($i = 0; $i < 8; ++ $i) {
                $password .= substr($cars, rand(0, $long - 1), 1);
            }
        }
        
        $m_user->setPassword(md5($password));
        if ($this->getMapper()->insert($m_user) <= 0) {
            throw new \Exception('error insert');
        }
        $id = (int) $this->getMapper()->getLastInsertValue();
        
        if ($school_id !== null) {
            $this->addSchool($school_id, $id, true);
            $this->getServiceContact()->addBySchool($school_id);
        }
        try {
            $this->getServiceMail()->sendTpl('tpl_createuser', $email, array('password' => $password,'email' => $email,'lastname' => $m_user->getLastname(),'firstname' => $m_user->getFirstname()));
        } catch (\Exception $e) {
            syslog(1, 'Model name does not exist <> password is : ' . $password . ' <> ' . $e->getMessage());
        }
        
        /*
         *
         * @todo check double role (instructor academic) autorisation
         */
        if ($roles === null) {
            $roles = [ModelRole::ROLE_STUDENT_STR];
        }
        if (! is_array($roles)) {
            $roles = array($roles);
        }
        
        if (! in_array(ModelRole::ROLE_SADMIN_STR, $this->getIdentity()['roles'])) {
            foreach ($roles as $r) {
                if ($r !== ModelRole::ROLE_STUDENT_STR && $r !== ModelRole::ROLE_ACADEMIC_STR && $r !== ModelRole::ROLE_INSTRUCTOR_STR) {
                    unset($r);
                }
            }
            if (empty($roles)) {
                $roles = [ModelRole::ROLE_STUDENT_STR];
            }
        }
        
        foreach ($roles as $r) {
            $this->getServiceUserRole()->add($this->getServiceRole()
                ->getIdByName($r), $id);
        }
        
        return $id;
    }

    /**
     * Import user data.
     *
     * @invokable
     *
     * @param array $data            
     *
     * @return array
     */
    public function import($data)
    {
        $error = [];
        foreach ($data as $u) {
            try {
                $id = $this->add($u['firstname'], $u['lastname'], $u['email'], null, null, null, $u['uid'], null, null, null, null, null, null, [$u['role']]);
            } catch (JrpcException $e) {
                $error[] = ['field' => $u,'code' => $e->getCode(),'message' => $e->getMessage()];
            }
        }
        
        return $error;
    }

    /**
     * Get List User By Item And User.
     *
     * @param int $item_id            
     * @param int $user_id            
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListUsersGroupByItemAndUser($item_id, $user_id = null)
    {
        if (null === $user_id) {
            $user_id = $this->getIdentity()['id'];
        }
        
        return $this->getMapper()->getListUsersGroupByItemAndUser($item_id, $user_id);
    }

    /**
     * Get List User By Submission.
     *
     * @param int $submission_id            
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListUsersBySubmission($submission_id)
    {
        return $this->getMapper()->getListUsersBySubmission($submission_id);
    }

    /**
     * Get List User By Item.
     *
     * @invokable
     *
     * @param int $item_id            
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListByItem($item_id)
    {
        return $this->getMapper()->getListByItem($item_id);
    }

    /**
     * if User Belong item.
     *
     * @param int $item_id            
     * @param int $user_id            
     *
     * @return bool
     */
    public function doBelongs($item_id, $user_id)
    {
        return $this->getMapper()->doBelongsByItemOfCourseUser($item_id, $user_id) && $this->getMapper()->doBelongsByItemHaveSubmission($item_id, $user_id);
    }

    /**
     * Get List By School.
     *
     * @param int $school_id            
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListBySchool($school_id)
    {
        return $this->getMapper()->getListBySchool($school_id);
    }

    /**
     * Get List User.
     *
     * @invokable
     *
     * @param string $filter            
     * @param string $type            
     * @param string $level            
     * @param string $course            
     * @param string $program            
     * @param string $search            
     * @param string $noprogram            
     * @param string $nocourse            
     * @param string $schools            
     * @param string $order            
     * @param array $exclude            
     * @param string $event            
     * @param string $message            
     *
     * @return array
     */
    public function getList($filter = null, $type = null, $level = null, $course = null, $program = null, $search = null, $noprogram = null, $nocourse = null, $schools = null, $order = null, array $exclude = null, $event = null, $message = null)
    {
        $identity = $this->getIdentity();
        
        if (in_array(ModelRole::ROLE_SADMIN_STR, $identity['roles']) && $schools === null) {
            $schools = false;
        }
        
        $mapper = $this->getMapper();
        $res = $mapper->usePaginator($filter)->getList($filter, $event, $identity['id'], $type, $level, $course, $program, $search, $noprogram, $nocourse, $schools, $order, $exclude, $message);
        
        $res = $res->toArray();
        foreach ($res as &$user) {
            $user['roles'] = [];
            $user['program'] = [];
            foreach ($this->getServiceRole()->getRoleByUser($user['id']) as $role) {
                $user['roles'][] = $role->getName();
            }
            $user['program'] = $this->getServiceProgram()->getListUser($user['id']);
        }
        
        return ['list' => $res,'count' => $mapper->count()];
    }

    /**
     * Get List user For Attendees
     *
     * @invokable
     *
     * @param array $course            
     * @param array $program            
     * @param array $school            
     * @param array $exclude_course            
     * @param array $exclude_program            
     * @param array $exclude_user            
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListAttendees($course = null, $program = null, $school = null, $exclude_course = null, $exclude_program = null, $exclude_user = null)
    {
        $res_user = $this->getMapper()->getListAttendees($course, $program, $school, $exclude_course, $exclude_program, $exclude_user);
        foreach ($res_user as $m_user) {
            $roles = [];
            foreach ($this->getServiceRole()->getRoleByUser($m_user->getId()) as $role) {
                $roles[] = $role->getName();
            }
            
            $m_user->setRoles($roles);
        }
        
        return $res_user;
    }

    /**
     * Get List By Type and Course.
     *
     * @param stirng $type            
     * @param int $course_id            
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListOnly($type, $course_id)
    {
        return $this->getMapper()->getList(null, null, $this->getIdentity()['id'], $type, null, $course_id, null, null, null, null, false);
    }

    /**
     * Get List Intructor By Item.
     *
     * @param int $item_id            
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getInstructorByItem($item_id)
    {
        return $this->getMapper()->getInstructorByItem($item_id);
    }

    /**
     * Get List Contact.
     *
     * @invokable
     *
     * @param int $type            
     * @param string $date            
     * @param int $user            
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListContact($type = 5, $date = null, $user = null)
    {
        if (null === $user) {
            $user = $this->getIdentity()['id'];
        }
        
        return $this->getMapper()->getListContact($user, $type, $date);
    }

    /**
     * Get List User By course.
     *
     * @param int $course_id            
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListUserBycourse($course_id)
    {
        return $this->getMapper()->getList(null, null, $this->getIdentity()['id'], null, null, $course_id, null, null, null, null, false);
    }

    /**
     * Get List User By course With Student And Instructor And Academic.
     *
     * @param int $course            
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListUserBycourseWithStudentAndInstructorAndAcademic($course_id)
    {
        return $this->getMapper()->getListUserBycourseWithStudentAndInstructorAndAcademic($course_id);
    }

    /**
     * Get List User By course With Instructor And Academic.
     *
     * @param int $course_id            
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListUserBycourseWithInstructorAndAcademic($course_id)
    {
        return $this->getMapper()->getListUserBycourseWithInstructorAndAcademic($course_id);
    }

    /**
     * Add user to Program.
     *
     * @invokable
     *
     * @param array $user            
     * @param array $program            
     *
     * @return array
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
     * Add User to Course.
     *
     * @invokable
     *
     * @param array $user            
     * @param array $course            
     *
     * @return array
     */
    public function addCourse($user, $course)
    {
        if (! is_array($user)) {
            $user = array($user);
        }
        if (! is_array($course)) {
            $course = array($course);
        }
        
        foreach ($user as $u) {
            foreach ($course as $c) {
                $r = $this->getRoleIds($u);
                if (in_array(ModelRole::ROLE_STUDENT_ID, $r)) {
                    $res_item = $this->getServiceItem()->getListByCourse($c);
                    foreach ($res_item as $m_item) {
                        // Si item est différent de Txt Document et Module
                        if ($m_item->getType() !== ModelItem::TYPE_TXT && $m_item->getType() !== ModelItem::TYPE_DOCUMENT && $m_item->getType() !== ModelItem::TYPE_MODULE && $m_item->getHasAllStudent() === 1) {
                            $this->getServiceSubmission()->addSubmissionUser($u, $m_item->getId());
                        }
                    }
                }
            }
        }
        
        return $this->getServiceCourseUserRelation()->add($user, $course);
    }

    /**
     * Delete user to Course.
     *
     * @invokable
     *
     * @param int|array $user            
     * @param int|array $course            
     *
     * @return array
     */
    public function deleteCourse($user, $course)
    {
        return $this->getServiceCourseUserRelation()->deleteCourse($user, $course);
    }

    /**
     * Delete user to program.
     *
     * @invokable
     *
     * @param int|array $user            
     * @param int|array $program            
     *
     * @return array
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
     * @param string $origin            
     * @param string $nationality            
     * @param string $firstname            
     * @param string $lastname            
     * @param string $sis            
     * @param string $email            
     * @param string $birth_date            
     * @param string $position            
     * @param int $school_id            
     * @param string $interest            
     * @param string $avatar            
     * @param array $roles            
     * @param array $programs            
     * @param string $resetpassword            
     * @param bool $has_email_notifier            
     * @param string $timezone            
     * @param string $background            
     * @param string $nickname            
     *
     * @return int
     */
    public function update($id = null, $gender = null, $origin = null, $nationality = null, $firstname = null, $lastname = null, $sis = null, $email = null, $birth_date = null, $position = null, $school_id = null, $interest = null, $avatar = null, $roles = null, $programs = null, $resetpassword = null, $has_email_notifier = null, $timezone = null, $background = null, $nickname = null)
    {
        if ($birth_date !== null && \DateTime::createFromFormat('Y-m-d', $birth_date) === false && \DateTime::createFromFormat('Y-m-d\TH:i:s.u\Z', $birth_date) === false) {
            $birth_date = null;
        }
        
        if ($this->getNbrEmailUnique($email, $id) > 0) {
            throw new JrpcException('duplicate email', - 38001);
        }
        
        $m_user = $this->getModel();
        
        if ($id === null) {
            $id = $this->getIdentity()['id'];
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
            ->setInterest($interest)
            ->setAvatar($avatar)
            ->setHasEmailNotifier($has_email_notifier)
            ->setTimezone($timezone)
            ->setBackground($background)
            ->setNickname($nickname)
            ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
        
        if ($school_id !== null) {
            if ($school_id === 'null') {
                $school_id = new IsNull('school_id');
            }
            $this->addSchool($school_id, $id, true);
        }
        
        if ($roles !== null) {
            if (! is_array($roles)) {
                $roles = [$roles];
            }
            $this->getServiceUserRole()->deleteByUser($id);
            foreach ($roles as $r) {
                $this->getServiceUserRole()->add($this->getServiceRole()
                    ->getIdByName($r), $id);
            }
        }
        
        if ($programs !== null) {
            $this->getServiceProgramUserRelation()->deleteByUser($id);
            $this->addProgram($id, $programs);
        }
        
        $ret = $this->getMapper()->update($m_user);
        
        if ($ret > 0 && $id === $this->getIdentity()['id']) {
            $this->getServiceEvent()->profileUpdated($id, $m_user->toArray());
        }
        
        if ($resetpassword) {
            $this->lostPassword($this->get($id)['email']);
        }
        
        // on supprime son cache identity pour qu'a ca prochaine cannection il el recré.
        $this->deleteCachedIdentityOfUser($id);
        
        return $ret;
    }

    /**
     * Add School relation
     *
     * @invokable
     *
     * @param int $school_id            
     * @param int $user_id            
     * @param bool $default            
     * @return NULL|int
     */
    public function addSchool($school_id, $user_id, $default = false)
    {
        $ret = null;
        $this->getServiceOrganizationUser()->add($school_id, $user_id);
        if ($default === true) {
            $ret = $this->getMapper()->update($this->getModel()
                ->setId($user_id)
                ->setSchoolId($school_id));
        }
        
        return $ret;
    }

    /**
     * Delete School relation
     *
     * @invokable
     * 
     * @param int $school_id            
     * @param int $user_id            
     * @return NULL|int
     */
    public function removeSchool($school_id, $user_id)
    {
        $m_user = $this->getMapper()
            ->select($this->getModel()
            ->getId($user_id))
            ->current();
        
        if ($m_user->getSchoolId() === $school_id) {
            throw new \JrpcException("Error, Cannot delete school by default");
        }
        
        return $this->getServiceOrganizationUser()->remove($school_id, $user_id);
    }

    /**
     * Get number of email.
     *
     * @param string $email            
     * @param int $user_id            
     *
     * @return int
     */
    public function getNbrEmailUnique($email, $user_id = null)
    {
        $res_user = $this->getMapper()->getEmailUnique($email, $user_id);
        
        return ($res_user->count() > 0) ? $res_user->current()->getNbUser() : 0;
    }

    /**
     * Get number of sis.
     *
     * @param string $sis            
     * @param int $user_id            
     *
     * @return int
     */
    public function getNbrSisUnique($sis, $user_id = null)
    {
        $res_user = $this->getMapper()->getNbrSisUnique($sis, $user_id);
        
        return ($res_user->count() > 0) ? $res_user->current()->getNbUser() : 0;
    }

    /**
     * Lost Password.
     *
     * @invokable
     *
     * @param string $email            
     */
    public function lostPassword($email)
    {
        $cars = 'azertyiopqsdfghjklmwxcvbn0123456789/*.!:;,....';
        $long = strlen($cars);
        srand((double) microtime() * 1000000);
        $password = '';
        for ($i = 0; $i < 8; ++ $i) {
            $password .= substr($cars, rand(0, $long - 1), 1);
        }
        
        $ret = $this->getMapper()->update($this->getModel()
            ->setNewPassword(md5($password)), array('email' => $email));
        
        if ($ret > 0) {
            $user = $this->getMapper()
                ->select($this->getModel()
                ->setEmail($email))
                ->current();
            
            try {
                $this->getServiceMail()->sendTpl('tpl_forgotpasswd', $email, array('password' => $password,'email' => $email,'lastname' => $user->getLastname(),'firstname' => $user->getFirstname()));
            } catch (\Exception $e) {
                syslog(1, 'Model name does not exist <> password is : ' . $password . ' <> ' . $e->getMessage());
            }
        }
        
        return $ret;
    }

    /**
     * Update Password.
     *
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
     * Get User.
     *
     * @invokable
     *
     * @param int $id            
     *
     * @return array
     */
    public function get($id = null)
    {
        $user_id = $this->getIdentity()['id'];
        
        if ($id === null) {
            $id = $user_id;
        }
        
        $res_user = $this->getMapper()->get($id, $user_id);
        if ($res_user->count() <= 0) {
            throw new \Exception('error get user: ' . $id);
        }
        
        $users = $res_user->toArray();
        foreach ($users as &$user) {
            $user['roles'] = [];
            $user['program'] = [];
            $user['program'] = $this->getServiceProgram()->getListByUser(null, $user['id'])['list'];
            foreach ($this->getServiceRole()->getRoleByUser($user['id']) as $role) {
                $user['roles'][] = $role->getName();
            }
        }
        
        return (count($users) > 1) ? $users : reset($users);
    }

    /**
     * Get Id Role of User.
     *
     * @param int $id            
     *
     * @return array
     */
    public function getRoleIds($id)
    {
        $ids = [];
        foreach ($this->getServiceRole()->getRoleByUser($id) as $m_role) {
            $ids[] = $m_role->getId();
        }
        
        return $ids;
    }

    /**
     * Get List Lite.
     *
     * @invokable
     *
     * @param int $id            
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListLite($id)
    {
        return $this->getMapper()->getListLite($id);
    }

    /**
     * Delete User.
     *
     * @invokable
     *
     * @param int $id            
     *
     * @return array
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
     * Get List Pair Graders.
     *
     * @invokable
     *
     * @param int $submission_id            
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListPairGraders($submission_id)
    {
        return $this->getMapper()->getListPairGraders($submission_id);
    }

    /**
     * Get List User Submission, Instrutor And Academic.
     *
     * @param int $submission_id            
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListBySubmissionWithInstrutorAndAcademic($submission_id)
    {
        return $this->getMapper()->getListBySubmissionWithInstrutorAndAcademic($submission_id);
    }

    /**
     * Get List.
     *
     * @param int $submission_id            
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListBySubmission($submission_id)
    {
        return $this->getMapper()->getListBySubmission($submission_id);
    }

    /**
     * Get user list for submission and those available.
     *
     * @param int $submission_id            
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListForSubmission($submission_id)
    {
        return $this->getMapper()->getListForSubmission($submission_id);
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
        $instructor = $this->getIdentity();
        if (in_array(ModelRole::ROLE_INSTRUCTOR_STR, $instructor['roles'])) {
            $ret = $this->getMapper()->getStudentList($instructor['id']);
        }
        
        return $ret;
    }

    /**
     * Get List user By conversation.
     *
     * @param int $conversation_id            
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListByConversation($conversation_id)
    {
        $res_user = $this->getMapper()->getListByConversation($conversation_id);
        foreach ($res_user as $m_user) {
            $roles = [];
            foreach ($this->getServiceRole()->getRoleByUser($m_user->getId()) as $role) {
                $roles[] = $role->getName();
            }
            $m_user->setRoles($roles);
        }
        
        return $res_user;
    }

    /**
     * Get List user By item assignment.
     *
     * @invokable
     *
     * @param int $item_assignment            
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListByItemAssignment($item_assignment)
    {
        $res_user = $this->getMapper()->getListByItemAssignment($item_assignment);
        
        foreach ($res_user as $m_user) {
            $roles = [];
            foreach ($this->getServiceRole()->getRoleByUser($m_user->getId()) as $role) {
                $roles[] = $role->getName();
            }
            $m_user->setRoles($roles);
        }
        
        return $res_user;
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
     * Get Number users by school.
     *
     * @param int $school_id            
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function nbrBySchool($school_id)
    {
        return $this->getMapper()->nbrBySchool($school_id);
    }
    
    // //////////////// EXTERNAL METHODE ///////////////////
    
    /**
     * Log In User.
     *
     * @invokable
     *
     * @param int $user            
     * @param int $password            
     *
     * @throws JrpcException
     *
     * @return array
     */
    public function auth($user, $password)
    {
        $auth = $this->getServiceAuth();
        $auth->getAdapter()->setIdentity($user);
        $auth->getAdapter()->setCredential($password);
        
        $result = $auth->authenticate();
        if (! $result->isValid()) {
            throw new JrpcException($result->getMessages()[0], $result->getCode()['code']);
        }
        
        return $this->getIdentity(true, true);
    }

    /**
     * Add User.
     *
     * @invokable
     *
     * @param string $email            
     * @param string $firstname            
     * @param string $lastname            
     * @param string $uid            
     * @param string $role            
     *
     * @return int
     */
    public function create($email, $firstname, $lastname, $uid, $role = null)
    {
        $id = $this->add($firstname, $lastname, $email, null, null, null, $uid, null, null, null, null, null, null, $role);
        
        return $this->get($id);
    }

    /**
     * Get Service Language.
     *
     * @return \Application\Service\Language
     */
    private function getServiceLanguage()
    {
        return $this->getServiceLocator()->get('app_service_language');
    }

    /**
     * Get List User.
     *
     * @invokable
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function listing()
    {
        return $this->getList();
    }

    /**
     * Get Service Program.
     *
     * @return \Application\Service\Program
     */
    private function getServiceProgram()
    {
        return $this->getServiceLocator()->get('app_service_program');
    }

    /**
     * Get Service ProgramUserRelation.
     *
     * @return \Application\Service\ProgramUserRelation
     */
    private function getServiceProgramUserRelation()
    {
        return $this->getServiceLocator()->get('app_service_program_user_relation');
    }

    /**
     * Get Service CourseUserRelation.
     *
     * @return \Application\Service\CourseUserRelation
     */
    private function getServiceCourseUserRelation()
    {
        return $this->getServiceLocator()->get('app_service_course_user_relation');
    }

    /**
     * Get Service UserLanguage.
     *
     * @return \Application\Service\UserLanguage
     */
    private function getServiceUserLanguage()
    {
        return $this->getServiceLocator()->get('app_service_user_language');
    }

    /**
     * Get Service Auth.
     *
     * @return \Zend\Authentication\AuthenticationService
     */
    private function getServiceAuth()
    {
        return $this->getServiceLocator()->get('auth.service');
    }

    /**
     * Get Service Role.
     *
     * @return \Application\Service\Role
     */
    private function getServiceRole()
    {
        return $this->getServiceLocator()->get('app_service_role');
    }

    /**
     * Get Service UserRole.
     *
     * @return \Application\Service\UserRole
     */
    private function getServiceUserRole()
    {
        return $this->getServiceLocator()->get('app_service_user_role');
    }

    /**
     * Get Storage if define in config.
     *
     * @return \Zend\Cache\Storage\StorageInterface
     */
    private function getCache()
    {
        $config = $this->getServiceLocator()->get('config')['app-conf'];
        
        return $this->getServiceLocator()->get($config['cache']);
    }

    /**
     * Get Service Event.
     *
     * @return \Application\Service\Event
     */
    private function getServiceEvent()
    {
        return $this->getServiceLocator()->get('app_service_event');
    }

    /**
     * Get Service Item.
     *
     * @return \Application\Service\Item
     */
    private function getServiceItem()
    {
        return $this->getServiceLocator()->get('app_service_item');
    }

    /**
     * Get Service Submission.
     *
     * @return \Application\Service\Submission
     */
    private function getServiceSubmission()
    {
        return $this->getServiceLocator()->get('app_service_submission');
    }

    /**
     * Get Service Contact.
     *
     * @return \Application\Service\Contact
     */
    private function getServiceContact()
    {
        return $this->getServiceLocator()->get('app_service_contact');
    }

    /**
     * Get Service Mail.
     *
     * @return \Mail\Service\Mail
     */
    private function getServiceMail()
    {
        return $this->getServiceLocator()->get('mail.service');
    }

    /**
     * Get Service OrganizationUser
     * 
     * @return \Application\Service\OrganizationUser
     */
    private function getServiceOrganizationUser()
    {
        return $this->getServiceLocator()->get('app_service_organization_user');
    }
    
    /**
     * Get Service Organization
     * 
     * @return \Application\Service\School
     */
    private function getServiceOrganization()
    {
        return $this->getServiceLocator()->get('app_service_school');
    }
}
