<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class GradingPolicyGradeComment extends AbstractService
{
    /**
     * @invokable
     *
     * @param int $grading_policy
     * @param int $user
     */
    public function getList($grading_policy, $user = null)
    {
        $identity = $this->getServiceUser()->getIdentity();
        if ($user === null || in_array(\Application\Model\Role::ROLE_STUDENT_STR, $identity['roles'])) {
            $user = $identity['id'];
        }
        $res_grading_policy_grade_comment = $this->getMapper()->getList($grading_policy, $user);

        foreach ($res_grading_policy_grade_comment as $m_grading_policy_grade_comment) {
            $m_grading_policy_grade_comment->getUser()->setRoles($this->getServiceRole()->getRoleByUser($m_grading_policy_grade_comment->getUser()->getId()));
        }

        return $res_grading_policy_grade_comment;
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
