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
    public function add($conversation_id, $submission_id)
    {
        return $this->getMapper()->insert($this->getModel()->setConversationId($conversation_id)->setSubmissionId($submission_id));
    }
}
