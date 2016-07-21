<?php
/**
 * 
 * TheStudnet (http://thestudnet.com)
 *
 * Submission Conversation
 *
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class SubConversation
 */
class SubConversation extends AbstractService
{
    /**
     * Add Submission to Conversation 
     * 
     * @param int $conversation_id
     * @param int $submission_id
     * @return int
     */
    public function add($conversation_id, $submission_id)
    {
        return $this->getMapper()->insert($this->getModel()->setConversationId($conversation_id)->setSubmissionId($submission_id));
    }
    
    /**
     * Get List Conversation
     * 
     * @param int $conversation_id
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getList($conversation_id)
    {
        return $this->getMapper()->select($this->getModel()->setConversationId($conversation_id));
    }
}
