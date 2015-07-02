<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class Message extends AbstractService
{
	/**
	 * Send message.
	 *
	 * @invokable
	 *
	 * @param int        $to
	 * @param string|int $contentOrId
	 *
	 * @throws \Exception
	 * @throws \Eception
	 *
	 * @return int
	 */
	public function send($contentOrId, $to = null, $conversation = null)
	{
		$identity = $this->getServiceUser()->getIdentity();
		
		$me = $identity['id'];
		 
		/*
		 * if $to
		 * on vérifie qu'il n'esiste pas de conversation deja existante
		 * 		if oui
		 * 		on récupaire la conversation id
		 * 		if non
		 * 		on créé la conversation
		 * else if $conversation
		 * on vérifie que la personne qui envoie le messge fait parti de la conversation
		 * 		if oui
		 * 		continue;
		 * 		if non
		 * 		exception;
		*/
		if(null!==$to) {
			if(!is_array($to)) {
				$to = array($to);
			}
			if(!in_array($me, $to)) {
				$to[] = $me;
			}
			$conversation = $this->getServiceConversationUser()->getConversationByUser($to);
		} elseif($conversation!==null) {
			if($this->getServiceConversationUser()->getByConversationUser($conversation, $me)->count() !== 1) {
				throw new \Exception('User ' . $me . ' is not in conversation ' . $conversation);
			}
		}
		 

		if (empty($contentOrId)) {
			throw new \Exception('error content is empty');
		}
		$m_message = $this->getModel()
			->setText($contentOrId)
			->setConversationId($conversation)
			->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
		
		if ($this->getMapper()->insert($m_message) <= 0) {
			throw new \Eception('error insert message');
		}
	
		$message_id = $this->getMapper()->getLastInsertValue();
		$message_user_id = $this->getServiceMessageUser()->send($message_id, $conversation);
	
		return $this->getServiceMessageUser()->getList($me, $message_id)->current();
	}
	
	/**
	 * @invokable
	 *
	 * Get List By user Conversation
	 *
	 * @param integer $conversation
	 */
	public function getList($conversation)
	{
		$identity = $this->getServiceUser()->getIdentity();
		$me = $identity['id'];
		 
		return $this->getServiceMessageUser()->getList($me, null, $conversation);
	}
	
	/**
	 * @invokable
	 *
	 * Get List Conversation
	 */
	public function getListConversation()
	{
		$identity = $this->getServiceUser()->getIdentity();
		$me = $identity['id'];
		 
		return $this->getServiceMessageUser()->getList($me);
	}
	
	/**
	 * @param int $id
	 *
	 * @return \Application\Model\Message
	 */
	public function getMessage($id)
	{
		$res_message = $this->getMapper()->select($this->getModel()->setId($id));
	
		if ($res_message->count() <= 0) {
			throw new \Exception('error get messge ');
		}
	
		return $res_message->current();
	}
	
	/**
	 * @return \Application\Service\User
	 */
	public function getServiceUser()
	{
		return $this->getServiceLocator()->get('app_service_user');
	}
	
	/**
	 * @return \Application\Service\MessageUser
	 */
	public function getServiceMessageUser()
	{
		return $this->getServiceLocator()->get('app_service_message_user');
	}
	
	/**
	 * @return \Application\Service\ConversationUser
	 */
	public function getServiceConversationUser()
	{
		return $this->getServiceLocator()->get('app_service_conversation_user');
	}
}
