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
        $select->columns(['id', 'user_id', 'from_id', 'read_date', 'message_id', 'conversation_id', 'created_date'])
            ->join(['message_user_message' => 'message'], 'message_user_message.id=message_user.message_id', ['id', 'is_draft', 'type', 'text', 'token', 'title', 'message$created_date' => new Expression("DATE_FORMAT(message_user_message.created_date, '%Y-%m-%dT%TZ') ")])
            ->join(['message_user_from' => 'user'], 'message_user_from.id=message_user.from_id', ['id', 'firstname', 'lastname', 'nickname', 'avatar'])
            ->where(['message_user.user_id' => $user_id])
            ->where(['message_user.deleted_date IS NULL'])
            ->where([' ( message_user_message.is_draft IS FALSE OR ( message_user_message.is_draft IS TRUE AND message_user_from.id = ? )) ' => $user_id])
            ->order(['message_user.id' => 'DESC']);

        if (null !== $message_id) {
            $select->where(['message_user_message.id' => $message_id]);
        } elseif (null !== $conversation_id) {
            $select->where(['message_user.conversation_id' => $conversation_id]);
            if (null !== $search) {
                $select->join('message_doc', 'message_doc.message_id=message.id', [], $select::JOIN_LEFT)
                    ->where(['( message_user_message.title LIKE ? ' => '%'.$search.'%'])
                    ->where(['CONCAT(message_user_from.firstname," ",message_user_from.lastname) LIKE ? ' => '%'.$search.'%'], Predicate::OP_OR)
                    ->where(['CONCAT(message_user_from.lastname," ",message_user_from.firstname) LIKE ? ' => '%'.$search.'%'], Predicate::OP_OR)
                    ->where(['message_user_from.nickname LIKE ? ' => ''.$search.'%'], Predicate::OP_OR)
                    ->where(['message_doc.name LIKE ? ) ' => '%'.$search.'%'], Predicate::OP_OR);
            }
        } else {
            $subselect = $this->tableGateway->getSql()->select();
            $subselect->columns(['message_user.id' => new Expression('MAX(message_user.id)')])
                ->join('message', 'message.id=message_user.message_id', [])
                ->where(['message_user.user_id' => $user_id])
                ->where(['message_user.deleted_date IS NULL'])
                ->group(['message_user.conversation_id']);

            if (null !== $type) {
                $subselect->where(['message.type' => $type]);
            }

            if (null !== $search) {
                $subselect->join('message_doc', 'message_doc.message_id=message.id', [], $select::JOIN_LEFT)
                    ->join('user', 'user.id=message_user.from_id', [])
                    ->where(['( message.title LIKE ? ' => '%'.$search.'%'])
                    ->where(['CONCAT(user.firstname," ",user.lastname) LIKE ? ' => '%'.$search.'%'], Predicate::OP_OR)
                    ->where(['CONCAT(user.lastname," ",user.firstname) LIKE ? ' => '%'.$search.'%'], Predicate::OP_OR)
                    ->where(['message_user_from.nickname LIKE ? ' => ''.$search.'%'], Predicate::OP_OR)
                    ->where(['message_doc.name LIKE ? ) ' => '%'.$search.'%'], Predicate::OP_OR);
            }

            switch ($tag) {
                case 'INBOX':
                    $subselect->where(['message.is_draft IS FALSE'])
                        ->where([' (message_user.type = ? ' => 'R'])
                        ->where(['message_user.type = ?) ' => 'RS'], Predicate::OP_OR);
                    break;
                case 'SENT':
                    $subselect->where(['message.is_draft IS FALSE'])
                        ->where([' ( message_user.type = ? ' => 'S'])
                        ->where(['message_user.type = ?) ' => 'RS'], Predicate::OP_OR);
                    break;
                case 'DRAFT':
                    $subselect->where(['message.is_draft IS TRUE'])
                        ->where(['message_user.user_id=message_user.from_id']);
                    break;
                case 'NOREAD':
                    $subselect->where(['message_user.read_date IS NULL'])
                        ->where(['message.is_draft IS FALSE'])
                        ->where([' (message_user.type = ? ' => 'R'])
                        ->where(['message_user.type = ?) ' => 'RS'], Predicate::OP_OR);
                    break;
                default:
                    $subselect->where([' ( message.is_draft IS FALSE OR ( message.is_draft IS TRUE AND message_user.from_id = ? )) ' => $user_id]);
                    break;
            }

            $select->where([new In('message_user.id', $subselect)]);
        }

        return $this->selectWith($select);
    }
    
    /**
     * Get List last Message
     * 
     * @param int $user_id
     * @param int $conversation_id
     */
    public function getListLastMessage($user_id, $conversation_id = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id', 'user_id', 'from_id', 'read_date', 'message_id', 'conversation_id', 'created_date'])
            ->join(['message_user_message' => 'message'], 'message_user_message.id=message_user.message_id', 
                ['id', 'text', 'token', 'title', 'message$created_date' => new Expression("DATE_FORMAT(message_user_message.created_date, '%Y-%m-%dT%TZ') ")])
            ->where(['message_user.user_id' => $user_id])
            ->where(['message_user.deleted_date IS NULL'])
            ->order(['message_user.id' => 'DESC']);
    
        $subselect = $this->tableGateway->getSql()->select();
        $subselect->columns(['message_user.id' => new Expression('MAX(message_user.id)')])
            ->join('message', 'message.id=message_user.message_id', [])
            ->where(['message_user.user_id' => $user_id])
            ->where(['message_user.deleted_date IS NULL'])
            ->group(['message_user.conversation_id'])
            ->where(['message.type' => 2]);
        
        if (null !== $conversation_id) {
            $subselect->where(['message_user.conversation_id' => $conversation_id]);
        }
            
        $select->where([new In('message_user.id', $subselect)]);
        
        return $this->selectWith($select);
    }

    public function countTag($user_id, $tag = 'INBOX', $type = 1)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['ok' => new Expression('true')])
            ->join('message', 'message.id=message_user.message_id', [])
            ->where(['message_user.user_id' => $user_id])
            ->where(['message_user.deleted_date IS NULL'])
            ->where(['message.type' => $type])
            ->group(['message_user.conversation_id']);

        switch ($tag) {
            case 'INBOX' :
                $select->where(['message.is_draft IS FALSE'])
                    ->where([' ( message_user.type = "R" OR message_user.type = "RS" )']);
                break;
            case 'SENT' :
                $select->where(['message.is_draft IS FALSE'])
                    ->where([' ( message_user.type = "S" OR message_user.type = "RS" )']);
                break;
            case 'DRAFT' :
                $select->where(['message.is_draft IS TRUE'])
                    ->where(['message_user.user_id=message_user.from_id']);
                break;
            case 'NOREAD' :
                $select->where(['message_user.read_date IS NULL'])
                    ->where(['message.is_draft IS FALSE'])
                    ->where([' ( message_user.type = "R" OR message_user.type = "RS" )']);
                break;
            default:
                ;
                break;
        }

        return $this->selectWith($select);
    }
}
