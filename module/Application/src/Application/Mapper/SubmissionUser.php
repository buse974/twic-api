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
        $select->columns(['submission_id', 'user_id', 'group_id', 'grade', 'started_date', 'finished_date'])
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
}