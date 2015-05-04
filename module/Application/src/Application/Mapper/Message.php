<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Dal\Db\Sql\Select;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\Sql\Predicate\In;
use Application\Model\Message as ModelMessage;

class Message extends AbstractMapper
{
    public function getListPreview($user, $tag = null)
    {
        $select = $this->tableGateway->getSql()->select(); //new Select();
        $select->columns(array('message.id' => new Expression('MAX(message.id)')))
               ->join('message_user', 'message_user.message_id=message.id', array(), $select::JOIN_LEFT)
               ->join('message_receiver', 'message_receiver.message_id=message.id', array())
               ->where(array('( message_user.user_id = ? ' => $user))
               ->where(array(' message_user.user_id IS NULL )'), 'OR')
               ->order(array('message.id' => 'DESC'))
               ->group(array('message.message_group_id'))
               ->quantifier('DISTINCT');

        if ($tag === ModelMessage::TYPE_DRAFT) {
            $select->where(array('message.draft IS TRUE'));
        } else {
            $select->where(array('message.draft IS FALSE'));
        }

        if ($tag === ModelMessage::TYPE_SENT) {
            $select->where(array('message_receiver.type = \'from\''));
        }

        if ($tag === ModelMessage::TYPE_DELETE) {
            $select->where(array('message_user.deleted_date IS NOT NULL'));
        } else {
            $select->where(array('message_user.deleted_date IS NULL'));
        }

        if ($tag === ModelMessage::TYPE_UNREAD) {
            $select->where(array('message_user.read_date IS NULL'));
        }

        $rselect = $this->tableGateway->getSql()->select();
        $rselect->columns(array('id', 'suject', 'content', 'message_group_id', 'created_date'))
                ->join('message_user', 'message_user.message_id=message.id', array('id', 'created_date', 'read_date', 'deleted_date'), $select::JOIN_LEFT)
                ->where(array(new In('message.id', $select)))
                ->where(array('( message_user.user_id = ? ' => $user))
                ->where(array(' message_user.user_id IS NULL )'), 'OR')
                ->quantifier('DISTINCT');

        return $this->selectWith($rselect);
    }

    public function getListByGroup($user, $group)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'suject', 'content', 'message_group_id', 'created_date'))
                ->join('message_user', 'message_user.message_id=message.id', array('id', 'created_date', 'read_date', 'deleted_date'), $select::JOIN_LEFT)
                ->where(array('message_user.user_id' => $user))
                ->where(array('message_user.deleted_date IS NULL'))
                ->where(array('message.message_group_id' => $group))
                ->order(array('message.id' => 'DESC'));

        return $this->selectWith($select);
    }
}
