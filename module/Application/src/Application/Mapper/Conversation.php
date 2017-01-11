<?php
/**
 * TheStudnet (http://thestudnet.com)
 *
 * Conversation
 */
namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Application\Model\Conversation as ModelConversation;
use Zend\Db\Sql\Expression;

/**
 * Class Conversation
 */
class Conversation extends AbstractMapper
{
    
    /**
     * Request Get List By Submission
     *
     * @param  int $submission_id
     * @param  int $user_id
     * @return \Dal\Db\ResultSet\ResultSet
     */
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

    /**
     * Request Get List By Item
     *
     * @param  int $item_id
     * @param  int $submission_id
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListByItem($item_id, $submission_id = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id', 'name', 'type', 'created_date'])
            ->join('sub_conversation', 'sub_conversation.conversation_id=conversation.id', [])
            ->join('submission', 'submission.id=sub_conversation.submission_id', [])
            ->where(array('submission.item_id = ? ' => $item_id))
            ->quantifier('DISTINCT');

        if (null !== $submission_id) {
            $select->where(array('submission.id' => $submission_id));
        }

        return $this->selectWith($select);
    }

    /**
     * Request Get List Id
     *
     * @param  int        $user_id
     * @param  int        $organization_id
     * @param  int        $program_id
     * @param  unkintnown $course_id
     * @param  int        $item_id
     * @param  int        $submission_id
     * @param  array      $users
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListId($user_id, $organization_id = null, $program_id = null, $course_id = null, $item_id = null, $submission_id = null, $users = null, $is_admin = false)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id'])
            ->join('sub_conversation', 'sub_conversation.conversation_id = conversation.id', [])
            ->join('submission', 'submission.id = sub_conversation.submission_id', [])
            ->join('item', 'item.id = submission.item_id', [])
            ->join('course', 'course.id = item.course_id', [])
            ->join('program', 'program.id = course.program_id', [])
            ->quantifier('distinct');
        if (true === $is_admin) {
            if (null === $organization_id) {
                $select->join('organization_user', 'organization_user.organization_id=program.school_id', [])
                    ->where(['organization_user.user_id' => $user_id]);
            } else {
                $select->where(['program.school_id' => $organization_id]);
            }
        } else {
            if (null === $users) {
                $users = [];
            }
            $users[] = $user_id;
        }
        if (!empty($users)) {
            $select->join('conversation_user', 'conversation_user.conversation_id=conversation.id', [])
                ->where(['conversation_user.user_id' => $users]);
        }
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
    
    public function m_getListUnread($id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id', 'conversation$nb_unread' => new Expression('SUM(IF(message_user.read_date IS NULL, 1, 0))')])
            ->join('message', 'message.conversation_id = conversation.id', [])
            ->join('message_user', 'message_user.message_id = message.id', [])
            ->where(['message_user.user_id' => $id])
            ->where(['conversation.type' => ModelConversation::TYPE_CHAT])
            ->group('conversation.id')
            ->having('conversation$nb_unread > 0');
        
        return $this->selectWith($select);
    }
}
