<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class VideoconfConversation extends AbstractModel
{
 	protected $videoconf_id;
	protected $conversation_id;

	protected $prefix = 'videoconf_conversation';

	public function getVideoconfId()
	{
		return $this->videoconf_id;
	}

	public function setVideoconfId($videoconf_id)
	{
		$this->videoconf_id = $videoconf_id;

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