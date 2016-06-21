<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class ConversationConversation extends AbstractModel
{
 	protected $id;
	protected $conversation_id;

	protected $prefix = 'conversation_conversation';

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	public function getConversationId()
	{
		return $this->conversation_id;
	}

	public function setConversationId($conversation_id)
	{
		$this->conversation_id = $conversation_id;

		return $this;
	}

}