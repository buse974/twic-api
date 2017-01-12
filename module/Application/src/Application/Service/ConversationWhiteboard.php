<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Conversation Whiteboard
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class ConversationWhiteboard.
 */
class ConversationWhiteboard extends AbstractService
{
    /**
     * Add Conversation Whiteboard.
     *
     * @param int $conversation_id
     * @param int $whiteboard_id
     *
     * @return int
     */
    public function add($conversation_id, $whiteboard_id)
    {
        return $this->getMapper()->insert(
            $this->getModel()
                ->setConversationId($conversation_id)
                ->setWhiteboardId($whiteboard_id)
        );
    }

    /**
     * Delete ConversationWhiteboard.
     *
     * @param int $whiteboard_id
     */
    public function delete($whiteboard_id)
    {
        $res_conversation_whiteboard = $this->getMapper()->select(
            $this->getModel()
                ->setWhiteboardId($whiteboard_id)
        );

        foreach ($res_conversation_whiteboard as $m_conversation_whiteboard) {
            $this->getMapper()->delete(
                $this->getModel()
                    ->setWhiteboardId($m_conversation_whiteboard->getWhiteboardId())
            );

            $this->getServiceWhiteboard()->delete($m_conversation_whiteboard->getWhiteboardId());
        }
    }

    /**
     * Get Service Whiteboard.
     *
     * @return \Application\Service\Whiteboard
     */
    public function getServiceWhiteboard()
    {
        return $this->container->get('app_service_whiteboard');
    }
}
