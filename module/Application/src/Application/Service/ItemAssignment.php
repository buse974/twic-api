<?php

namespace Application\Service;

use Dal\Service\AbstractService;
use Application\Model\Item as CItem;
use DateTime;
use DateTimeZone;

class ItemAssignment extends AbstractService
{
    /**
     * @invokable
     *
     * @param int $item_prog
     *
     * @return array
     */
    public function getSubmission($item_prog)
    {
        $user = $this->getServiceUser()->getIdentity()['id'];
        $res_item_assignment = $this->getFromItemProg($user, $item_prog);
        if ($res_item_assignment->count() > 0) {
            return $this->get($res_item_assignment->current()
                ->getId());
        }

        return $this->get($this->add($item_prog));
    }

    public function getIdByItemProg($item_prog)
    {
        $m_item_assigment = $this->getModel()->setItemProgId($item_prog);

        $res_item_assigment = $this->getMapper()->select($m_item_assigment);

        if ($res_item_assigment->count() <= 0) {
            throw new \Exception('no item_assigment');
        }

        return $res_item_assigment->current()->getId();
    }

    /**
     * @invokable
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return \Application\Model\ItemAssignment
     */
    public function get($id)
    {
        /*
         $m_item_prog = $this->getServiceItemProg()->getByItemAssignment($id);
        
         $datetime1 = new \DateTime($m_item_prog->getStartDate());
         $datetime2 = new \DateTime('now', new DateTimeZone('UTC'));
        
         if ($datetime1 > $datetime2) {
            return false;
         }
         */

        $res_item_assignement = $this->getMapper()->get($id);

        if ($res_item_assignement->count() == 0) {
            throw new \Exception('no item_assigment');
        }

        $m_item_assignment = $res_item_assignement->current();

        $m_item_assignment->setStudents($this->getServiceUser()
            ->getListByItemAssignment($id))
            ->setDocuments($this->getServiceItemAssignmentDocument()
            ->getListByItemAssignment($id))
            ->setComments($this->getServiceItemAssignmentComment()
            ->getListByItemAssignment($id));

        $m_item = $m_item_assignment->getItemProg()->getItem();
        $m_item->setMaterials($this->getServiceMaterialDocument()
            ->getListByItem($m_item->getId()));

        $m_course = $m_item->getCourse();
        $m_course->setInstructor($this->getServiceUser()
            ->getListOnly(\Application\Model\Role::ROLE_INSTRUCTOR_STR, $m_course->getId()));

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
     * @param bool   $submit
     */
    public function add($item_prog, $response = null, $documents = null, $submit = false)
    {
        $m_item_prog = $this->getServiceItemProg()->get($item_prog);

        /*
         * $datetime1 = new \DateTime($m_item_prog->getStartDate());
         * $datetime2 = new \DateTime('now', new DateTimeZone('UTC'));
         *
         * condition a voir
         *
         * if ($datetime1 > $datetime2) {
         * throw new \Exception('error date');
         * }
         */

        $m_item_assignment = $this->getModel()
            ->setItemProgId($item_prog)
            ->setResponse($response);
        if ($submit) {
            $m_item_assignment->setSubmitDate((new DateTime('now', new DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
        }

        if ($this->getMapper()->insert($m_item_assignment) <= 0) {
            throw new \Exception('error insert item assignment');
        }

        $m_item = $this->getServiceItem()->getByItemProg($item_prog);
        $item_assigment_id = $this->getMapper()->getLastInsertValue();

        if (is_array($documents)) {
            foreach ($documents as $d) {
                $this->addDocument($item_assigment_id, $d);
            }
        }

        switch ($m_item->getType()) {
            case CItem::TYPE_WORKGROUP:
                $res_item_prog_user = $this->getServiceItemProgUser()->getListByItemProg($item_prog);
                foreach ($res_item_prog_user as $m_item_prog_user) {
                    $this->getServiceItemAssignmentRelation()->add($m_item_prog_user->getId(), $item_assigment_id);
                }
                break;

            case (CItem::TYPE_INDIVIDUAL_ASSIGMENT || CItem::TYPE_CAPSTONE_PROJECT):
                $res_item_prog_user = $this->getServiceItemProgUser()->getListByItemProg($item_prog, $this->getServiceAuth()
                    ->getIdentity()
                    ->getId());

                if ($res_item_prog_user->count() <= 0) {
                    $this->getMapper()->delete($this->getModel()
                        ->setId($item_assigment_id));
                    throw new \Exception('error insert item assignment');
                }
                $this->getServiceItemAssignmentRelation()->add($res_item_prog_user->current()
                    ->getId(), $item_assigment_id);
                break;
        }

        return $item_assigment_id;
    }

    /**
     * @invokable
     *
     * @param int   $id
     * @param array $document
     */
    public function addDocument($id, $document)
    {
        $type = isset($document['type']) ? $document['type'] : null;
        $title = isset($document['title']) ? $document['title'] : null;
        $author = isset($document['author']) ? $document['author'] : null;
        $link = isset($document['link']) ? $document['link'] : null;
        $source = isset($document['source']) ? $document['source'] : null;
        $token = isset($document['token']) ? $document['token'] : null;
        $date = isset($document['date']) ? $document['date'] : null;

        return $this->getServiceItemAssignmentDocument()->add($id, $type, $title, $author, $link, $source, $token, $date);
    }

    /**
     * @invokable
     *
     * @param int $document
     */
    public function removeDocument($document)
    {
        return $this->getServiceItemAssignmentDocument()->delete($document);
    }

    /**
     * @invokable
     *
     * @param string $text
     * @param int    $item_assignment
     * @param string $file
     * @param string $file_name
     */
    public function addComment($text, $item_assignment, $file = null, $file_name = null)
    {
        $item_assignment_comment = $this->getServiceItemAssignmentComment()->add($item_assignment, $text, $file, $file_name);

        $this->getServiceEvent()->assignmentCommented($item_assignment, $item_assignment_comment);

        return $item_assignment_comment;
    }

    /**
     * @invokable
     *
     * @param int $item_assignment
     * @param int $score
     */
    public function setGrade($item_assignment, $score)
    {
        $item_prog_id = $this->getMapper()
            ->select($this->getModel()
            ->setId($item_assignment))
            ->current()
            ->getItemProgId();

        $res_item_assignment_relation = $this->getServiceItemAssignmentRelation()->getByItemAssignment($item_assignment);
        foreach ($res_item_assignment_relation as $m_item_assignment_relation) {
            $m_item_prog_user = $this->getServiceItemProgUser()
                ->getById($m_item_assignment_relation->getItemProgUserId())
                ->current();
            $this->getServiceItemGrading()->add($m_item_assignment_relation->getItemProgUserId(), $score);
            $this->getServiceGradingPolicyGrade()->process($m_item_assignment_relation->getItemAssignmentId(), $m_item_prog_user->getUserId());
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
    public function update($id, $documents = null, $response = null, $submit = false)
    {
        $m_item_assignment = $this->getMapper()
            ->select($this->getModel()
            ->setId($id))
            ->current();

        if ($m_item_assignment->getSubmitDate() instanceof \Zend\Db\Sql\Predicate\IsNull) {
            $m_item = $this->getServiceItem()->getByItemProg($m_item_assignment->getItemProgId());

            if ($response !== null) {
                $m_item_assignment->setResponse(strip_tags(htmlspecialchars_decode(htmlentities($response)), '<div><span><p><strong><img><hr><u><a><ol><ul><li>'));
            }
            if (is_array($documents)) {
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
            if ($m_item->getType() === CItem::TYPE_INDIVIDUAL_ASSIGMENT || $m_item->getType() === CItem::TYPE_CAPSTONE_PROJECT) {
                $this->getServiceItemProgUser()->start($m_item_assignment->getItemProgId());
            }
            if ($submit) {
                $m_item_assignment->setSubmitDate((new DateTime('now', new DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
                $this->getServiceEvent()->studentSubmitAssignment($id);
                if ($m_item->getType() === CItem::TYPE_INDIVIDUAL_ASSIGMENT || $m_item->getType() === CItem::TYPE_CAPSTONE_PROJECT) {
                    $this->getServiceItemProgUser()->end($m_item_assignment->getItemProgId());
                }
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
        $ret = $this->getMapper()->update($this->getModel()
            ->setId($id)
            ->setSubmitDate((new DateTime('now', new DateTimeZone('UTC')))->format('Y-m-d H:i:s')));

        $this->getServiceEvent()->studentSubmitAssignment($id);

        return $ret;
    }

    public function submitByItemProg($item_prog)
    {
        $ret = $this->getMapper()->update($this->getModel()
            ->setSubmitDate((new DateTime('now', new DateTimeZone('UTC')))->format('Y-m-d H:i:s')), array('item_prog_id' => $item_prog));

        $this->getServiceEvent()->studentSubmitAssignment($this->getIdByItemProg($item_prog));

        return $ret;
    }

    public function deleteByItemProg($item_prog)
    {
        $res_item_assignment = $this->getMapper()->select($this->getModel()
            ->setItemProgId($item_prog));

        foreach ($res_item_assignment as $m_item_assignment) {
            $this->getServiceItemAssignmentDocument()->deleteByItemAssignment($m_item_assignment->getId());
            $this->getServiceItemAssignmentComment()->deleteByItemAssignment($m_item_assignment->getId());
            $this->getServiceItemAssignmentRelation()->deleteByItemAssignment($m_item_assignment->getId());
        }

        return $this->getMapper()->delete($this->getModel()
            ->setItemProgId($item_prog));
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
     * @return \Application\Service\ItemAssignmentRelation
     */
    public function getServiceItemAssignmentRelation()
    {
        return $this->getServiceLocator()->get('app_service_item_assignment_relation');
    }

    /**
     * @return \Application\Service\Event
     */
    public function getServiceEvent()
    {
        return $this->getServiceLocator()->get('app_service_event');
    }

    /**
     * @return \Zend\Authentication\AuthenticationService
     */
    public function getServiceAuth()
    {
        return $this->getServiceLocator()->get('auth.service');
    }

    /**
     * @return \Application\Service\GradingPolicyGrade
     */
    public function getServiceGradingPolicyGrade()
    {
        return $this->getServiceLocator()->get('app_service_grading_policy_grade');
    }
}
