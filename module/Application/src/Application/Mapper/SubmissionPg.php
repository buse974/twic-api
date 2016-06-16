<?php

namespace Application\Mapper;

use Dal\Db\Sql\Select;
use Zend\Db\Sql\Predicate\Expression;
use Dal\Mapper\AbstractMapper;

class SubmissionPg extends AbstractMapper
{
    public function deleteNotIn($submission, $users)
    {
        $delete = $this->tableGateway->getSql()->delete();
        $delete->where(array('submission_id' => $submission));

        if (empty($users)) {
            $delete->where(new NotIn('user_id', $users));
        }

        return $this->deleteWith($delete);
    }

    /**
     * @param int $submission
     * @param int $user
     * 
     * @return int
     */
    public function checkGraded($submission, $user)
    {
        $having = new \Zend\Db\Sql\Having();
        $having->expression('COUNT(DISTINCT pg_user_grade.user_id) = COUNT(DISTINCT submission_user.user_id)', []);

        $select = new Select('pg_user_grade');
        $select->columns(['has_graded' => new Expression('COUNT(DISTINCT pg_user_grade.user_id) = COUNT(DISTINCT submission_user.user_id)')])
               ->join('submission_user', 'submission_user.submission_id = pg_user_grade.submission_id', [])
               ->where(['submission_user.submission_id' => $submission])
               ->where(['pg_user_grade.pg_id' => $user]);

        $update = $this->tableGateway->getSql()->update();
        $update->set(['has_graded' => $select])
               ->where(['user_id' => $user])
               ->where(['submission_id' => $submission]);

        return $this->updateWith($update);
    }
}
