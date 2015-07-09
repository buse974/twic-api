<?php

namespace Application\Service;

use Dal\Service\AbstractService;
use Application\Model\Item as CItem;
use DateTime;
use DateTimeZone;
use JRpc\Json\Server\Exception\JrpcException;

class ItemAssignment extends AbstractService
{
    
      /**
     * @invokable
     *
     * @param int $item_prog     *
     *
     * @return array
     */
    public function getSubmission($item_prog)
    {
        $user = $this->getServiceUser()->getIdentity()['id'];
        $res_item_assignment = $this->getFromItemProg($user, $item_prog);
        if($res_item_assignment->count() > 0){
            return $this->get($res_item_assignment->current()->getId());
        }
        return $this->get($this->add($item_prog));
        
    }
    
    /**
     * @invokable
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return array
     */
    public function get($id)
    {
        $user = $this->getServiceUser()->getIdentity()['id'];
        $res_item_assignement = $this->getMapper()->get($id, $user);

        if ($res_item_assignement->count() == 0) {
            throw new JrpcException('No authorization', -32029);
        }

        $m_item_assignment = $res_item_assignement->current();
        $m_item_assignment->setStudents($this->getServiceUser()->getListByItemAssignment($id));
        $m_item_assignment->setDocuments($this->getServiceItemAssignmentDocument()->getListByItemAssignment($id));
        $m_item_assignment->setComments($this->getServiceItemAssignmentComment()->getListByItemAssignment($id));
        $m_item = $m_item_assignment->getItemProg()->getItem();
        $m_item->setMaterials($this->getServiceMaterialDocument()->getListByItem($m_item->getId()));
        $m_course = $m_item->getCourse();
        $m_course->setInstructor($this->getServiceUser()->getListOnly(\Application\Model\Role::ROLE_INSTRUCTOR_STR, $m_course->getId()));
           
        return $m_item_assignment;        
        
    }
    
    /**
     * @invokable
     *
     * @param int $user
     * @param int $item_prog
     *
     * @return array
     */
    public function getFromItemProg($user, $item_prog)
    {
        return $this->getMapper()->getFromItemProg($user, $item_prog);
    }
    

    /**
     * @invokable
     *
     * @param int    $item_prog
     * @param string $response
     * @param array  $documents
     * @param bool  $submit
     */
    public function add($item_prog, $response = null, $documents = null, $submit = false)
    {
        $m_item_assignment = $this->getModel()->setItemProgId($item_prog)->setResponse($response);
        if($submit){
                $m_item_assignment->setSubmitDate((new DateTime('now', new DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
        }

        if ($this->getMapper()->insert($m_item_assignment) <= 0) {
            throw new \Exception('error insert item prog');
        }
        $m_item = $this->getServiceItem()->getByItemProg($item_prog);
        $item_assigment_id = $this->getMapper()->getLastInsertValue();

        if (is_array($documents)) {
            foreach ($documents as $d) {
                $type = isset($d['type']) ? $d['type'] : null;
                $title = isset($d['title']) ? $d['title'] : null;
                $author = isset($d['author']) ? $d['author'] : null;
                $link = isset($d['link']) ? $d['link'] : null;
                $source = isset($d['source']) ? $d['source'] : null;
                $token = isset($d['token']) ? $d['token'] : null;
                $date = isset($d['date']) ? $d['date'] : null;

                $this->getServiceItemAssignmentDocument()->add($item_assigment_id, $type, $title, $author, $link, $source, $token, $date);
            }
        }

        switch ($m_item->getType()) {
            case CItem::TYPE_WORKGROUP:
                $res_item_prog = $this->getServiceItemProgUser()->getListByItemProg($item_prog);
                foreach ($res_item_prog as $m_item_prog) {
                    $this->getServiceItemAssignmentUser()->add(
                            $m_item_prog->getUserId(),
                            $item_assigment_id);
                }
            break;

            case (CItem::TYPE_INDIVIDUAL_ASSIGMENT || CItem::TYPE_CAPSTONE_PROJECT):
                $this->getServiceItemAssignmentUser()->add(
                        $this->getServiceAuth()->getIdentity()->getId(),
                        $item_assigment_id);
            break;
        }

        return $item_assigment_id;
    }

    /**
     * @invokable
     *
     * @param string $text
     * @param int    $item_assignment
     */
    public function addComment($text, $item_assignment)
    {
        return $this->getServiceItemAssignmentComment()->add($item_assignment, $text);
    }

    /**
     * @invokable
     *
     * @param int $item_assignment
     * @param int $score
     */
    public function setGrade($item_assignment, $score)
    {
        $item_prog_id = $this->getMapper()->select($this->getModel()->setId($item_assignment))->current()->getItemProgId();

        $res_item_assignment_user = $this->getServiceItemAssignmentUser()->getByItemAssignment($item_assignment);
        foreach ($res_item_assignment_user as $m_item_assignment_user) 
        {
            $item_prog_user_id = $this->getServiceItemProgUser()->get($item_prog_id, $m_item_assignment_user->getUserId())->current()->getId();
            $this->getServiceItemGrading()->add($item_prog_user_id, $score);          
            $this->getServiceGradingPolicyGrade()->process($m_item_assignment_user->getItemAssignmentId(), $m_item_assignment_user->getUserId());
        }

        return true;
    } 
    
    /**
     * @invokable
     *
     * @param int $id
     *
     * @return int
     */
    public function update($id, $documents = null,  $response = null, $submit = false)
    {
        $user = $this->getServiceUser()->getIdentity()['id'];
        $students = $this->getServiceUser()->getListByItemAssignment($id);
        $res_item_assignment = array();
        foreach($students as $student){
            if($student->getId() === $user){
                $res_item_assignment = $this->getMapper()->select($this->getModel()->setId($id));
                break;
            }
        }
        $m_item_assignment = $res_item_assignment->current();
        if($m_item_assignment->getSubmitDate() instanceof \Zend\Db\Sql\Predicate\IsNull){
            
            if($response !== null){
                $m_item_assignment->setResponse(strip_tags(htmlspecialchars_decode(htmlentities($response)), '<div><span><p><strong><img><hr><u><a><ol><ul><li>'));
            }
            if (is_array($documents)){
                $this->getServiceItemAssignmentDocument()->deleteByItemAssignment($id);
                foreach ($documents as $d) {
                    $type = isset($d['type']) ? $d['type'] : null;
                    $title = isset($d['title']) ? $d['title'] : null;
                    $author = isset($d['author']) ? $d['author'] : null;
                    $link = isset($d['link']) ? $d['link'] : null;
                    $source = isset($d['source']) ? $d['source'] : null;
                    $token = isset($d['token']) ? $d['token'] : null;
                    $date = isset($d['date']) ? $d['date'] : null;

                    $this->getServiceItemAssignmentDocument()->add($id, $type, $title, $author, $link, $source, $token, $date);
                }
            }
            if($submit){
                $m_item_assignment->setSubmitDate((new DateTime('now', new DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
            }

            return $this->getMapper()->update($m_item_assignment);
        }
        return 0;
    }

    /**
     * @invokable
     *
     * @param int $id
     *
     * @return int
     */
    public function submit($id)
    {
        
        $m_item_assignement = $this->getModel()->setId($id)->setSubmitDate((new DateTime('now', new DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
        
        return $this->getMapper()->update($m_item_assignement);
    }

    public function deleteByItemProg($item_prog)
    {
        $res_item_assignment = $this->getMapper()->select($this->getModel()->setItemProgId($item_prog));

        foreach ($res_item_assignment as $m_item_assignment) {
            $this->getServiceItemAssignmentDocument()->deleteByItemAssignment($m_item_assignment->getId());
            $this->getServiceItemAssignmentComment()->deleteByItemAssignment($m_item_assignment->getId());
            $this->getServiceItemAssignmentUser()->deleteByItemAssignment($m_item_assignment->getId());
        }

        return $this->getMapper()->delete($this->getModel()->setItemProgId($item_prog));
    }

    /**
     * @return \Application\Service\ItemAssigmentDocument
     */
    public function getServiceItemAssignmentDocument()
    {
        return $this->getServiceLocator()->get('app_service_item_assigment_document');
    }

    /**
     * @return \Application\Service\Item
     */
    public function getServiceItem()
    {
        return $this->getServiceLocator()->get('app_service_item');
    }

    /**
     * @return \Application\Service\ItemGrading
     */
    public function getServiceItemGrading()
    {
        return $this->getServiceLocator()->get('app_service_item_grading');
    }

    /**
     * @return \Application\Service\ItemProg
     */
    public function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }

    /**
     * @return \Application\Service\ItemProgUser
     */
    public function getServiceItemProgUser()
    {
        return $this->getServiceLocator()->get('app_service_item_prog_user');
    }

    /**
     * @return \Application\Service\ItemProg
     */
    public function getServiceItemProg()
    {
        return $this->getServiceLocator()->get('app_service_item_prog');
    }

    /**
     * @return \Application\Service\MaterialDocument
     */
    public function getServiceMaterialDocument()
    {
        return $this->getServiceLocator()->get('app_service_material_document');
    }

    /**
     * @return \Application\Service\ItemAssignmentComment
     */
    public function getServiceItemAssignmentComment()
    {
        return $this->getServiceLocator()->get('app_service_item_assignment_comment');
    }

    /**
     * @return \Application\Service\ItemAssignmentUser
     */
    public function getServiceItemAssignmentUser()
    {
        return $this->getServiceLocator()->get('app_service_item_assignment_user');
    }

    /**
     * @return \Zend\Authentication\AuthenticationService
     */
    public function getServiceAuth()
    {
        return $this->getServiceLocator()->get('auth.service');
    }

    /**
     * @return \Zend\Authentication\GradingPolicyGrade
     */
    public function getServiceGradingPolicyGrade()
    {
        return $this->getServiceLocator()->get('app_service_grading_policy_grade');
    }
}
