<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\Sql\Predicate\In;
use Zend\Db\Sql\Predicate\Predicate;

class MessageUser extends AbstractMapper
{
    /**
     * Request Get list MessageUser.
     * 
     * @param int    $user_id
     * @param int    $message_id
     * @param int    $conversation_id
     * @param string $tag
     * @param string $type
     * @param array  $filter
     * @param string $search
     *
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getList($user_id, $message_id = null, $conversation_id = null, $tag = 'INBOX', $type = null, $filter = null, $search = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'user_id', 'from_id', 'read_date', 'conversation_id', 'created_date'))
            ->join(array('message_user_message' => 'message'), 'message_user_message.id=message_user.message_id', array('id', 'is_draft', 'type', 'text', 'token', 'title', 'message$created_date' => new Expression("DATE_FORMAT(message_user_message.created_date, '%Y-%m-%dT%TZ') ")))
            ->join(array('message_user_from' => 'user'), 'message_user_from.id=message_user.from_id', array('id', 'firstname', 'lastname', 'nickname', 'avatar'))
            ->where(array('message_user.user_id' => $user_id))
            ->where(array('message_user.deleted_date IS NULL'))
            ->where(array(' ( message_user_message.is_draft IS FALSE OR ( message_user_message.is_draft IS TRUE AND message_user_from.id = ? )) ' => $user_id))
            ->order(array('message_user.id' => 'DESC'));

        if (null !== $message_id) {
            $select->where(array('message_user_message.id' => $message_id));
        } elseif (null !== $conversation_id) {
            $select->where(array('message_user.conversation_id' => $conversation_id));
            if (null !== $search) {
                $select->join('message_doc', 'message_doc.message_id=message.id', array(), $select::JOIN_LEFT)
                    ->where(array('( message_user_message.title LIKE ? ' => '%'.$search.'%'))
                    ->where(array('CONCAT(message_user_from.firstname," ",message_user_from.lastname) LIKE ? ' => '%'.$search.'%'), Predicate::OP_OR)
                    ->where(array('CONCAT(message_user_from.lastname," ",message_user_from.firstname) LIKE ? ' => '%'.$search.'%'), Predicate::OP_OR)
                    ->where(array('message_user_from.nickname LIKE ? ' => ''.$search.'%'), Predicate::OP_OR)
                    ->where(array('message_doc.name LIKE ? ) ' => '%'.$search.'%'), Predicate::OP_OR);
            }
        } else {
            $subselect = $this->tableGateway->getSql()->select();
            $subselect->columns(array('message_user.id' => new Expression('MAX(message_user.id)')))
                ->join('message', 'message.id=message_user.message_id', array())
                ->where(array('message_user.user_id' => $user_id))
                ->where(array('message_user.deleted_date IS NULL'))
                ->group(array('message_user.conversation_id'));

            if (null !== $type) {
                $subselect->where(array('message.type' => $type));
            }

            if (null !== $search) {
                $subselect->join('message_doc', 'message_doc.message_id=message.id', array(), $select::JOIN_LEFT)
                    ->join('user', 'user.id=message_user.from_id', array())
                    ->where(array('( message.title LIKE ? ' => '%'.$search.'%'))
                    ->where(array('CONCAT(user.firstname," ",user.lastname) LIKE ? ' => '%'.$search.'%'), Predicate::OP_OR)
                    ->where(array('CONCAT(user.lastname," ",user.firstname) LIKE ? ' => '%'.$search.'%'), Predicate::OP_OR)
                    ->where(array('message_user_from.nickname LIKE ? ' => ''.$search.'%'), Predicate::OP_OR)
                    ->where(array('message_doc.name LIKE ? ) ' => '%'.$search.'%'), Predicate::OP_OR);
            }

            switch ($tag) {
                case 'INBOX':
                    $subselect->where(array('message.is_draft IS FALSE'))
                        ->where(array(' (message_user.type = ? ' => 'R'))
                        ->where(array('message_user.type = ?) ' => 'RS'), Predicate::OP_OR);
                    break;
                case 'SENT':
                    $subselect->where(array('message.is_draft IS FALSE'))
                        ->where(array(' ( message_user.type = ? ' => 'S'))
                        ->where(array('message_user.type = ?) ' => 'RS'), Predicate::OP_OR);
                    break;
                case 'DRAFT':
                    $subselect->where(array('message.is_draft IS TRUE'))
                        ->where(array('message_user.user_id=message_user.from_id'));
                    break;
                case 'NOREAD':
                    $subselect->where(array('message_user.read_date IS NULL'))
                        ->where(array('message.is_draft IS FALSE'))
                        ->where(array(' (message_user.type = ? ' => 'R'))
                        ->where(array('message_user.type = ?) ' => 'RS'), Predicate::OP_OR);
                    break;
                default:
                    $subselect->where(array(' ( message.is_draft IS FALSE OR ( message.is_draft IS TRUE AND message_user.from_id = ? )) ' => $user_id));
                    break;
            }

            $select->where(array(new In('message_user.id', $subselect)));
        }

        return $this->selectWith($select);
    }

    public function countTag($user_id, $tag = 'INBOX', $type = 1)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['ok' => new Expression('true')])
            ->join('message', 'message.id=message_user.message_id', [])
            ->where(array('message_user.user_id' => $user_id))
            ->where(array('message_user.deleted_date IS NULL'))
            ->where(array('message.type' => $type))
            ->group(array('message_user.conversation_id'));

        switch ($tag) {
            case 'INBOX' :
                $select->where(array('message.is_draft IS FALSE'))
                    ->where(array(' ( message_user.type = "R" OR message_user.type = "RS" )'));
                break;
            case 'SENT' :
                $select->where(array('message.is_draft IS FALSE'))
                    ->where(array(' ( message_user.type = "S" OR message_user.type = "RS" )'));
                break;
            case 'DRAFT' :
                $select->where(array('message.is_draft IS TRUE'))
                    ->where(array('message_user.user_id=message_user.from_id'));
                break;
            case 'NOREAD' :
                $select->where(array('message_user.read_date IS NULL'))
                    ->where(array('message.is_draft IS FALSE'))
                    ->where(array(' ( message_user.type = "R" OR message_user.type = "RS" )'));
                break;
            default:
                ;
                break;
        }

        return $this->selectWith($select);
    }
}
