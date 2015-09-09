<?php
namespace Application\Service;

use Dal\Service\AbstractService;
use DateTime;
use DateTimeZone;
use Application\Model\Role as ModelRole;

class Course extends AbstractService
{

    /**
     * Add course.
     *
     * @invokable
     *
     * @param int $program_id            
     * @param string $title            
     * @param string $abstract            
     * @param string $description            
     * @param string $objectives            
     * @param string $teaching            
     * @param string $attendance            
     * @param string $duration            
     * @param string $notes            
     * @param string $learning_outcomes            
     * @param string $video_link            
     * @param string $video_token            
     * @param array $material_document            
     *
     * @throws \Exception
     *
     * @return int
     */
    public function add($program_id, $title = null, $picture = null, $abstract = null, $description = null, $objectives = null, $teaching = null, $attendance = null, $duration = null, $notes = null, $learning_outcomes = null, $video_link = null, $video_token = null, array $material_document = array())
    {
        $m_course = $this->getModel()
            ->setTitle($title)
            ->setCreatorId($this->getServiceAuth()
            ->getIdentity()
            ->getId())
            ->setAbstract($abstract)
            ->setPicture($picture)
            ->setDescription($description)
            ->setObjectives($objectives)
            ->setTeaching($teaching)
            ->setAttendance($attendance)
            ->setDuration($duration)
            ->setNotes($notes)
            ->setLearningOutcomes($learning_outcomes)
            ->setVersion(1)
            ->setVideoLink($video_link)
            ->setVideoToken($video_token)
            ->setProgramId($program_id)
            ->setCreatedDate((new DateTime('now', new DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
        
        $res = $this->getMapper()->insert($m_course);
        if ($res <= 0) {
            throw new \Exception('error insert course');
        }
        
        $course_id = $this->getMapper()->getLastInsertValue();
        $this->getServiceGradingPolicy()->initTpl($course_id);
        
        foreach ($material_document as $md) {
            if (isset($md['type'])) {
                $type = (isset($md['type']) ? $md['type'] : null);
                $title = (isset($md['title']) ? $md['title'] : null);
                $author = (isset($md['author']) ? $md['author'] : null);
                $link = (isset($md['link']) ? $md['link'] : null);
                $source = (isset($md['source']) ? $md['source'] : null);
                $token = (isset($md['token']) ? $md['token'] : null);
                $date = (isset($md['date']) ? $md['date'] : null);
                $this->getServiceMaterialDocument()->add($course_id, $type, $title, $author, $link, $source, $token, $date);
            }
        }
        
        return $this->get($course_id);
    }

    /**
     * Update course.
     *
     * @invokable
     *
     * @param int $id            
     * @param string $title            
     * @param string $abstract            
     * @param string $description            
     * @param string $objectives            
     * @param string $teaching            
     * @param string $attendance            
     * @param string $duration            
     * @param string $notes            
     * @param string $learning_outcomes            
     * @param string $video_link            
     * @param string $video_token            
     *
     * @return int
     */
    public function update($id, $title = null, $picture = null, $abstract = null, $description = null, $objectives = null, $teaching = null, $attendance = null, $duration = null, $notes = null, $learning_outcomes = null, $video_link = null, $video_token = null)
    {
        $m_course = $this->getModel()
            ->setId($id)
            ->setTitle($title)
            ->setAbstract($abstract)
            ->setPicture($picture)
            ->setDescription($description)
            ->setObjectives($objectives)
            ->setTeaching($teaching)
            ->setAttendance($attendance)
            ->setDuration($duration)
            ->setNotes($notes)
            ->setLearningOutcomes($learning_outcomes)
            ->setVideoLink($video_link)
            ->setVideoToken($video_token)
            ->setUpdatedDate((new DateTime('now', new DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
        
        $ret = $this->getMapper()->update($m_course);
        
        if($ret > 0) {
            $ar_course = $m_course->toArray();
            unset($ar_course['updated_date']);
            $this->getServiceNotification()->courseUpdated($id, $ar_course);
        }
        
        return $ret;
    }

    /**
     * Delete course.
     *
     * @invokable
     *
     * @param array $id            
     *
     * @return int
     */
    public function delete($id)
    {
        $ret = array();
        
        if (! is_array($id)) {
            $id = array($id);
        }
        
        $m_course = $this->getModel()->setDeletedDate((new DateTime('now', new DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
        
        foreach ($id as $idc) {
            if ($ret[$idc] = $this->getMapper()->update($m_course, array('id' => $idc)) > 0) {
                $this->getServiceMaterialDocument()->deleteByCourseId($idc);
            }
        }
        
        return $ret;
    }

    /**
     * @invokable
     *
     * @param int $id            
     *
     * @throws \Exception
     *
     * @return \Application\Model\Course
     */
    public function get($id)
    {
        $res_couse = $this->getMapper()->get($id);
        
        if ($res_couse->count() == 0) {
            throw new \Exception('no course with id: ' . $id);
        }
        
        $m_course = $res_couse->current();
        $m_course->setMaterialDocument($this->getServiceMaterialDocument()
            ->getListByCourse($id));
        $m_course->setGrading($this->getServiceGrading()
            ->getByCourse($id));
        $m_course->setGradingPolicy($this->getServiceGradingPolicy()
            ->get($id));
        $m_course->setInstructor($this->getServiceUser()
            ->getListOnly(ModelRole::ROLE_INSTRUCTOR_STR, $m_course->getId()));
        
        return $m_course;
    }

    /**
     * @invokable
     *
     * @param int $program            
     * @param string $search            
     * @param array $filter            
     *
     * @return array
     */
    public function getList($program = null, $search = null, $filter = null)
    {
        $mapper = $this->getMapper();
        $res_course = $mapper->usePaginator($filter)->getList($program, $search, $filter);
        
        foreach ($res_course as $m_course) {
            $m_course->setStudent($this->getServiceUser()
                ->getListOnly(ModelRole::ROLE_STUDENT_STR, $m_course->getId()));
            $m_course->setInstructor($this->getServiceUser()
                ->getListOnly(ModelRole::ROLE_INSTRUCTOR_STR, $m_course->getId()));
        }
        
        return array('count' => $mapper->count(),'list' => $res_course);
    }

    /**
     * @invokable
     */
    public function getListRecord()
    {
        $user = $this->getServiceUser()->getIdentity();
        $is_student = (array_key_exists(ModelRole::ROLE_STUDENT_ID, $user['roles'])) ? true : false;
        $res_course = $this->getMapper()->getListRecord($user['id'], $is_student);
        
        foreach ($res_course as $m_course) {
            $m_course->setItems($this->getServiceItem()
                ->getListRecord($m_course->getId(), $user['id'], $is_student));
        }
        
        return $res_course;
    }

    /**
     *
     * @return \Application\Service\MaterialDocument
     */
    public function getServiceMaterialDocument()
    {
        return $this->getServiceLocator()->get('app_service_material_document');
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
     * @return \Application\Service\Grading
     */
    public function getServiceGrading()
    {
        return $this->getServiceLocator()->get('app_service_grading');
    }

    /**
     *
     * @return \Application\Service\Module
     */
    public function getServiceModule()
    {
        return $this->getServiceLocator()->get('app_service_module');
    }

    /**
     *
     * @return \Application\Service\Item
     */
    public function getServiceItem()
    {
        return $this->getServiceLocator()->get('app_service_item');
    }

    /**
     *
     * @return \Application\Service\User
     */
    public function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }

    /**
     *
     * @return \Application\Service\GradingPolicy
     */
    public function getServiceGradingPolicy()
    {
        return $this->getServiceLocator()->get('app_service_grading_policy');
    }
    
    /**
     *
     * @return \Application\Service\Notification
     */
    public function getServiceNotification()
    {
        return $this->getServiceLocator()->get('app_service_notification');
    }
    
}
