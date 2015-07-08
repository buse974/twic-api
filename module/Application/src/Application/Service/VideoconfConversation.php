<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class VideoconfConversation extends AbstractService
{
	/**
	 * 
	 * @param integer $conversation
	 * @param integer $videoconf
	 * @throws \Exception
	 * @return integer
	 */
	public function add($conversation, $videoconf) 
	{
		if($this->getMapper()->insert($this->getModel()->setConversationId($conversation)->setVideoconfId($videoconf)) <= 0) {
			throw new \Exception('error insert videoconf conversation');
		}
		
		return $this->getMapper()->getLastInsertValue();
	}
	
	/**
	 * @param integer $conversation
	 * @param integer $videoconf
	 */
	public function delete($conversation, $videoconf) 
	{
		return $this->getMapper()->delete($this->getModel()->setConversationId($conversation)->setVideoconfId($videoconf));
	}
}