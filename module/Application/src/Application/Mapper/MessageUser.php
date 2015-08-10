<?php
namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\Sql\Predicate\In;

class MessageUser extends AbstractMapper
{
    public function getList($me, $message = null, $conversation = null, $tag = 'INBOX', $type = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id','user_id','from_id','read_date','conversation_id','created_date'))
            ->join(array('message_user_message' => 'message'), 'message_user_message.id=message_user.message_id', array('id','type','text','token','message$created_date' => new Expression("DATE_FORMAT(message_user_message.created_date, '%Y-%m-%dT%TZ') ")))
            ->join(array('message_user_from' => 'user'), 'message_user_from.id=message_user.from_id', array('id','firstname','lastname','avatar'))
            ->where(array('message_user.user_id' => $me))
            ->where(array('message_user.deleted_date IS NULL'))
            ->order(array('message_user.id' => 'DESC'));
        
        if (null !== $message) {
            $select->where(array('message_user_message.id' => $message));
        } elseif (null !== $conversation) {
            $select->where(array('message_user.conversation_id' => $conversation));
        } else {
            $subselect = $this->tableGateway->getSql()->select();
            $subselect->columns(array('message_user.id' => new Expression('MAX(message_user.id)')))
                ->join('message', 'message.id=message_user.message_id', array())
                ->where(array('message_user.user_id' => $me))
                ->where(array('message_user.deleted_date IS NULL'))
                ->group(array('message_user.conversation_id'));
            
            if(null !== $type) {
                $subselect->where(array('message.type' => $type));
            }
            
            switch ($tag) {
                case 'INBOX':
                    $subselect->where(array('message_user.deleted_date IS NULL'))
                        ->where(array('message_user.read_date IS NULL'))
                        ->where(array('message.is_draft IS FALSE'));
                    break;
                case 'SENT':
                    $subselect->where(array('message_user.deleted_date IS NULL'))
                        ->where(array('message.is_draft IS FALSE'))
                        ->where(array('message_user.user_id=message_user.from_id'));
                    break;
                case 'DRAFT':
                    $subselect->where(array('message_user.deleted_date IS NULL'))
                        ->where(array('message.is_draft IS TRUE'))
                        ->where(array('message_user.user_id=message_user.from_id'));
                    break;
                default:
                    ;
                    break;
            }
            $select->where(array(new In('message_user.id', $subselect)));
        }
        
        return $this->selectWith($select);
    }
}
