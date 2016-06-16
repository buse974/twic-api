<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class SubmissionComments extends AbstractMapper
{
    /* 
     * @param integer $id
     * 
     * @return \Application\Model\SubmissionComments
     */
    public function get($id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->join('user', 'submission_comments.user_id = user.id', ['id', 'firstname', 'lastname', 'avatar'])
                ->where(['submission_comments.id' => $id]);

        return $this->selectWith($select);
    }

     /* 
     * @param integer $submission
     * 
     * @return array
     */
    public function getList($submission)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->join('user', 'submission_comments.user_id = user.id', ['id', 'firstname', 'lastname', 'avatar'])
                ->where(['submission_comments.submission_id' => $submission]);

        return $this->selectWith($select);
    }
}
