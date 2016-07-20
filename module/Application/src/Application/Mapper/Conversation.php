<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class Conversation extends AbstractMapper
{
    public function getListBySubmission($submission_id, $user_id = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id', 'name', 'type', 'created_date'])
            ->join('sub_conversation', 'sub_conversation.conversation_id=conversation.id', array())
            ->join('conversation_user', 'conversation_user.conversation_id=conversation.id', array())
            ->where(array('sub_conversation.submission_id = ? ' => $submission_id))
            ->quantifier('DISTINCT');

        if (null !== $user_id) {
            $select->where(array('conversation_user.user_id' => $user_id));
        }

        return $this->selectWith($select);
    }

    public function getListByItem($item_id, $submission_id = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id', 'name', 'type', 'created_date'])
            ->join('sub_conversation', 'sub_conversation.conversation_id=conversation.id', array())
            ->join('submission', 'submission.id=sub_conversation.submission_id', array())
            ->where(array('submission.item_id = ? ' => $item_id))
            ->quantifier('DISTINCT');

        if (null !== $submission_id) {
            $select->where(array('submission.id' => $submission_id));
        }

        return $this->selectWith($select);
    }
    
    public function getListId($school_id, $program_id = null, $course_id = null, $item_id = null, $submission_id = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id'])
            ->join('sub_conversation', 'sub_conversation.conversation_id = conversation.id', [])
            ->join('submission', 'submission.id = sub_conversation.submission_id', [])
            ->join('item', 'item.id = submission.item_id', [])
            ->join('course', 'course.id = item.course_id', [])
            ->join('program', 'program.id = course.program_id', [])
            ->where(['program.school_id' => $school_id])
            ->quantifier('distinct');
        
        if (null !== $item_id) {
            $select->where(['item.id' => $item_id]);
        } 
        if (null !== $course_id) {
            $select->where(['course.id' => $course_id]);
        } 
        if (null !== $program_id) {
            $select->where(['program.id' => $program_id]);
        } 
        if (null !== $submission_id) {
            $select->where(['submission.id' => $submission_id]);
        }
    
        return $this->selectWith($select);
    }
}
