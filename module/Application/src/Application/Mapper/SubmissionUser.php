<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\Expression;
use Dal\Db\Sql\Select;

class SubmissionUser extends AbstractMapper
{
    
    /**
     * @param integer $submission_id
     * @param integer $user_id
     *
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getListBySubmissionId($submission_id, $user_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['submission_id', 'user_id', 'grade', 'submit_date','start_date'])
            ->join('user', 'user.id=submission_user.user_id', ['user$id' => new Expression('user.id'),
                'firstname',
                'gender',
                'lastname',
                'email',
                'has_email_notifier',
                'user$birth_date' => new Expression('DATE_FORMAT(user.birth_date, "%Y-%m-%dT%TZ")'),
                'position',
                'interest',
                'avatar',
                'school_id',
                'user$contact_state' => $this->getSelectContactState($user_id)
            ])
            ->where(array('submission_user.submission_id' => $submission_id));
    
        return $this->selectWith($select);
    }
        
    /**
     * @param integer $user
     * @return \Zend\Db\Sql\Select
     */
    private function getSelectContactState($user)
    {
        $select = new Select('user');
        $select->columns(array('user$contact_state' =>  new Expression(
            'IF(contact.accepted_date IS NOT NULL, 3,
	         IF(contact.request_date IS NOT  NULL AND contact.requested <> 1, 2,
		     IF(contact.request_date IS NOT  NULL AND contact.requested = 1, 1,0)))')))
    		     ->join('contact', 'contact.contact_id = user.id', array())
    		     ->where(array('user.id=`user$id`'))
    		     ->where(['contact.user_id' => $user ]);
    
    	return $select;
    }
    
    /**
     * @param integer $submission
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getProcessedGrades($submission)
    {
        $select = new Select('submission_user_criteria');
        $select->columns([
            'submission_user$submission_id' => 'submission_id',
            'submission_user$user_id' => 'user_id',
            'submission_user$grade' => new Expression('IF(COUNT(DISTINCT criteria.id) = COUNT(DISTINCT submission_user_criteria.criteria_id), ROUND(SUM(submission_user_criteria.points) * 100 / SUM(criteria.points)), NULL)')])
           ->join('submission','submission_user_criteria.submission_id = submission.id',[])
           ->join('item', 'submission.item_id = item.id', [])
           ->join('grading_policy', 'item.grading_policy_id = grading_policy.id', [])
           ->join('criteria', 'criteria.grading_policy_id = grading_policy.id', [])
           ->where(['submission_user_criteria.submission_id' => $submission])
           ->group(['submission_user_criteria.submission_id', 'submission_user_criteria.user_id']);
        
        return $this->selectWith($select);
    }
    
    /**
     * @param integer $submission
     * @return boolean
     */
    public function checkAllFinish($submission_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('submission_id'))
            ->where(array('submission_user.end_date IS NULL'))
            ->where(array('submission_user.start_date IS NOT NULL'))
            ->where(array('submission_user.submission_id' => $submission_id));
        
        return ($this->selectWith($select)->count() === 0) ? true : false;
    }
}