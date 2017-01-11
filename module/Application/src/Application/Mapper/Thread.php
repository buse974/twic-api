<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\Expression;

class Thread extends AbstractMapper
{
    public function getList($course = null, $thread = null, $name = null, $submission_id = null)
    {
        if (null === $course && null === $thread && null === $submission_id) {
            throw new \Exception('no params');
        }

        $select = $this->tableGateway->getSql()->select();
        $select->columns(
            array(
            'id',
            'title',
            'item_id',
            'thread$created_date' => new Expression('DATE_FORMAT(thread.created_date, "%Y-%m-%dT%TZ")'),
            'thread$deleted_date' => new Expression('DATE_FORMAT(thread.deleted_date, "%Y-%m-%dT%TZ")'),
            )
        )
            ->join(
                array(
                'thread_user' => 'user',
                ), 'thread_user.id=thread.user_id', array(
                'id',
                'firstname',
                'lastname',
                'nickname',
                'avatar',
                'thread$nb_message' => new Expression('SUM(IF(thread_message.id IS NULL OR thread_message.deleted_date IS NOT NULL, 0,1))'),
                )
            )
            ->join('thread_message', 'thread_message.thread_id=thread.id', array(), $select::JOIN_LEFT)
            ->join(
                'course', 'thread.course_id=course.id', array(
                'id',
                'title',
                ), $select::JOIN_LEFT
            )
            ->where(
                array(
                'thread.deleted_date IS NULL',
                )
            )
            ->group('thread.id');

        if (null !== $name) {
            $select->where(
                array(
                'thread.title LIKE ? ' => $name.'%',
                )
            );
        }
        if (null !== $course) {
            $select->where(
                array(
                'thread.course_id' => $course,
                )
            )->where(
                    [
                    'thread.item_id IS NULL',
                    ]
            );
        }
        if (null !== $thread) {
            $select->where(
                array(
                'thread.id' => $thread,
                )
            );
        }
        if (null !== $submission_id) {
            $select->join('submission', 'submission.item_id=thread.item_id', [])->where(
                array(
                'submission.id' => $submission_id,
                )
            );
        }

        return $this->selectWith($select);
    }

    public function getNbrMessage($school, $day = null)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(
            array(
            'thread$nb_message' => new Expression('COUNT(true)'),
            )
        )
            ->join('user', 'user.id=thread.user_id', array())
            ->join('thread_message', 'thread_message.thread_id=thread.id', array())
            ->where(
                array(
                'user.school_id' => $school,
                )
            )
            ->where(
                array(
                'thread.deleted_date IS NULL',
                )
            );

        if (null !== $day) {
            $select->where(
                array(
                'thread_message.created_date > DATE_ADD(UTC_TIMESTAMP(), INTERVAL -'.$day.' DAY) ',
                )
            );
        }

        return $this->selectWith($select)
            ->current()
            ->getNbMessage();
    }
}
