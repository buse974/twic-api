<?php

namespace Application\Service;

use Dal\Service\AbstractService;
use DateTime;
use DateTimeZone;

class ItemAssignmentComment extends AbstractService
{
    public function add($item_assignment_id, $text, $file = null, $file_name = null)
    {
        $m_item_assignment_comment = $this->getModel()->setItemAssignmentId($item_assignment_id)
                                                      ->setText($text)
                                                      ->setFile($file)
                                                      ->setFileName($file_name)
                                                      ->setUserId($this->getServiceAuth()->getIdentity()->getId())
                                                      ->setCreatedDate((new DateTime('now', new DateTimeZone('UTC')))->format('Y-m-d H:i:s'));

        if($this->getMapper()->insert($m_item_assignment_comment) <= 0) {
            throw new \Exception('error insert comment');
        }
        
        return $this->getMapper()->getLastInsertValue();
    }

    public function deleteByItemAssignment($item_assignment)
    {
        return $this->getMapper()->delete($this->getModel()->setItemAssignmentId($item_assignment));
    }

    /**
     * @invokable
     *
     * @param int $item
     * @param int $user
     */
    public function getList($item, $user = null)
    {
        $identity = $this->getServiceUser()->getIdentity();
        if ($user === null || in_array(\Application\Model\Role::ROLE_STUDENT_STR, $identity['roles'])) {
            $user = $identity['id'];
        }
        $res_item_assignment_comment = $this->getMapper()->getList($item, $user);

        foreach ($res_item_assignment_comment as $m_item_assignment_comment) {
            $m_item_assignment_comment->getUser()->setRoles($this->getServiceRole()->getRoleByUser($m_item_assignment_comment->getUser()->getId()));
        }

        return $res_item_assignment_comment;
    }
    
    /**
     * 
     * @param integer $item_assignment_comment
     * 
     * @return \Application\Model\ItemAssignmentComment
     */
    public function get($item_assignment_comment)
    {
        return $this->getMapper()->select($this->getModel()->setId($item_assignment_comment))->current();
    }

    /**
     * @invokable
     *
     * @param int $item_assignment
     */
    public function getListByItemAssignment($item_assignment)
    {
        $res_item_assignment_comment = $this->getMapper()->getListByItemAssignment($item_assignment);

        foreach ($res_item_assignment_comment as $m_item_assignment_comment) {
            $m_item_assignment_comment->getUser()->setRoles($this->getServiceRole()->getRoleByUser($m_item_assignment_comment->getUser()->getId()));
        }

        return $res_item_assignment_comment;
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
     * @return \Application\Service\User
     */
    public function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }
}
