<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Dal\Db\Sql\Select;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\Sql\Predicate\In;
use Application\Model\Mail as ModelMail;


class Mail extends AbstractMapper
{
	public function getListPreview($user, $tag = null)
	{
		$select = $this->tableGateway->getSql()->select(); //new Select();
		$select->columns(array('mail.id' => new Expression('MAX(mail.id)')))
		->join('mail_user', 'mail_user.mail_id=mail.id', array(), $select::JOIN_LEFT)
		->join('mail_receiver', 'mail_receiver.mail_id=mail.id', array())
		->where(array('( mail_user.user_id = ? ' => $user))
		->where(array(' mail_user.user_id IS NULL )'), 'OR')
		->order(array('mail.id' => 'DESC'))
		->group(array('mail.mail_group_id'))
		->quantifier('DISTINCT');
	
		if ($tag === ModelMail::TYPE_DRAFT) {
			$select->where(array('mail.draft IS TRUE'));
		} else {
			$select->where(array('mail.draft IS FALSE'));
		}
	
		if ($tag === ModelMail::TYPE_SENT) {
			$select->where(array('mail_receiver.type = \'from\''));
		}
	
		if ($tag === ModelMail::TYPE_DELETE) {
			$select->where(array('mail_user.deleted_date IS NOT NULL'));
		} else {
			$select->where(array('mail_user.deleted_date IS NULL'));
		}
	
		if ($tag === ModelMail::TYPE_UNREAD) {
			$select->where(array('mail_user.read_date IS NULL'));
		}
	
		$rselect = $this->tableGateway->getSql()->select();
		$rselect->columns(array('id', 'suject', 'content', 'mail_group_id', 'created_date'))
		->join('mail_user', 'mail_user.mail_id=mail.id', array('id', 'created_date', 'read_date', 'deleted_date'), $select::JOIN_LEFT)
		->where(array(new In('mail.id', $select)))
		->where(array('( mail_user.user_id = ? ' => $user))
		->where(array(' mail_user.user_id IS NULL )'), 'OR')
		->quantifier('DISTINCT');
	
		return $this->selectWith($rselect);
	}
	
	public function getListByGroup($user, $group)
	{
		$select = $this->tableGateway->getSql()->select();
		$select->columns(array('id', 'suject', 'content', 'mail_group_id', 'created_date'))
		->join('mail_user', 'mail_user.mail_id=mail.id', array('id', 'created_date', 'read_date', 'deleted_date'), $select::JOIN_LEFT)
		->where(array('mail_user.user_id' => $user))
		->where(array('mail_user.deleted_date IS NULL'))
		->where(array('mail.mail_group_id' => $group))
		->order(array('mail.id' => 'DESC'));
	
		return $this->selectWith($select);
	}
}