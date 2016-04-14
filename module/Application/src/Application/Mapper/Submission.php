<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Expression;
use Dal\Db\Sql\Select;

class Submission extends AbstractMapper
{
    /**
     * 
     * @param integer $user
     * @param integer $questionnaire
     * 
     * @return \Application\Model\Submission
     */
    public function getByUserAndQuestionnaire($user, $questionnaire)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id'))
            ->join('questionnaire', 'questionnaire.item_id=submission.item_id', array())
            ->join('submission_user', 'submission_user.submission_id=submission.id', array())
            ->where(array('submission_user.user_id' => $user))
            ->where(array('questionnaire.id' => $questionnaire));
    
        return $this->selectWith($select)->current();
    }
    
    /**
     * @param integer $item_id
     * @param integer $user_id
     * @param integer $me
     * 
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function get($item_id, $user_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'item_id'))
            ->join('submission_user', 'submission_user.submission_id=submission.id', array())
            ->where(array('submission.item_id' => $item_id))
            ->where(array('submission_user.user_id' => $user_id));

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