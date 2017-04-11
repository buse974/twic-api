<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\Sql\Predicate\Predicate;
use Zend\Db\Sql\Predicate\In;
use Application\Model\Conversation as ModelConversation;

class Conversation extends AbstractMapper
{
  public function getList($user_id, $conversation_id = null, $type = null)
  {
      $select = $this->tableGateway->getSql()->select();
      $select->columns(['conversation$id' => new Expression('conversation.id'), 'created_date', 'name','type'])
        ->join(['conversation_message' => 'message'], 'conversation.id=conversation_message.conversation_id', ['id', 'text', 'conversation_message$created_date' => new Expression('DATE_FORMAT(conversation_message.created_date, "%Y-%m-%dT%TZ")')])
        ->join(['conversation_message_library' => 'library'], 'conversation_message_library.id=conversation_message.library_id', ['id', 'name', 'link', 'token', 'type', 'box_id'], $select::JOIN_LEFT)
        ->join(['conversation_message_message_user' => 'message_user'], new Expression('conversation_message.id=conversation_message_message_user.message_id AND conversation_message_message_user.user_id = ?', [$user_id]) ,  [], $select::JOIN_LEFT)
        ->order(['conversation_message.id DESC']);

      $subselect = $this->tableGateway->getSql()->select();
      $subselect->columns([])
        ->join('conversation_user', 'conversation_user.conversation_id=conversation.id', [])
        ->join('message', 'conversation.id=message.conversation_id', ['message.id' => new Expression('MAX(message.id)')])
        ->join('message_user', new Expression('message.id=message_user.message_id AND message_user.user_id = ?', [$user_id]),[], $select::JOIN_LEFT)
        ->where(['conversation_user.user_id' => $user_id])
        ->where(['message_user.deleted_date IS NULL'])
        ->group(['conversation.id']);

      if(null !== $type) {
        $subselect->where(['conversation.type' => $type]);
      }

      if (null !== $conversation_id) {
        $subselect->where(['conversation.id' => $conversation_id]);
      }

      $select->where([new In('conversation_message.id', $subselect)]);

      return $this->selectWith($select);
  }

  public function getId($user_id, $contact = null, $noread = null, $type = null, $search = null)
  {
      $subsubselect = $this->tableGateway->getSql()->select();
      $subselect = $this->tableGateway->getSql()->select();
      $select = $this->tableGateway->getSql()->select();
      $select->columns(['id', 'type'])
        ->join(['conversation_message' => 'message'], 'conversation.id=conversation_message.conversation_id', ['id'])
        ->where([new In('conversation_message.id', $subsubselect)])
        ->order(['conversation_message.id DESC']);

      // READ OR NOT READ
      if(true === $noread) {
        $select->join(['conversation_message_message_user' => 'message_user'], new Expression('conversation_message.id=conversation_message_message_user.message_id AND conversation_message_message_user.user_id = ?', [$user_id]) ,  [], $select::JOIN_LEFT);
        $select->where(['conversation_message_message_user.read_date IS NULL']);
      }

      // ONLY ONE CONTACT OR NOT
      if(true === $contact || false === $contact) {
        $select->join('conversation_user', 'conversation.id=conversation_user.conversation_id',[], $select::JOIN_LEFT);
        $select->join('contact', new Expression('contact.contact_id=conversation_user.user_id AND contact.user_id = ?', [$user_id]), ['is_contact' => new Expression('IF(contact.deleted_date IS NULL AND contact.accepted_date IS NOT NULL, TRUE, FALSE)')], $select::JOIN_LEFT)
          ->where(['conversation_user.user_id <> ?' => $user_id])
          ->group(['conversation.id']);

          if($contact) {
            $select->having('COUNT(true) = 1 AND is_contact IS TRUE');
          } else {
            $select->having('!(COUNT(true) = 1 AND is_contact IS TRUE)');
          }
      }

        $subsubselect->columns([])
          ->join(['conversation_message' => 'message'], 'conversation.id=conversation_message.conversation_id', ['id'])
          ->where([new In('conversation_message.id', $subselect)]);

      if(null !== $search) {
        $subsubselect->join('conversation_user', 'conversation.id=conversation_user.conversation_id',[], $select::JOIN_LEFT)
            ->join('user', 'user.id=conversation_user.user_id',[], $select::JOIN_LEFT)
            ->where(array('(conversation.name LIKE ? ' => ''.$search.'%'))
            ->where(array('CONCAT_WS(" ", user.firstname, user.lastname) LIKE ? ' => ''.$search.'%'), Predicate::OP_OR)
            ->where(array('CONCAT_WS(" ", user.lastname, user.firstname) LIKE ? ' => ''.$search.'%'), Predicate::OP_OR)
            ->where(array('user.nickname LIKE ? )' => ''.$search.'%'), Predicate::OP_OR);
        }

      $subselect->columns([])
        ->join('conversation_user', 'conversation_user.conversation_id=conversation.id', [])
        ->join('message', 'conversation.id=message.conversation_id', ['message.id' => new Expression('MAX(message.id)')])
        ->join('message_user', new Expression('message.id=message_user.message_id AND message_user.user_id = ?', [$user_id]),[], $select::JOIN_LEFT)
        ->where(['conversation_user.user_id' => $user_id])
        ->where(['message_user.deleted_date IS NULL'])
        ->group(['conversation.id']);

      // TYPE
      if(null !== $type) {
        $subselect->where(['conversation.type' => $type]);
      }

      // SI S-S-REQUETE
    /*  if(null !== $search) {
        $sselect
      }*/


      return $this->selectWith($select);
  }
}
