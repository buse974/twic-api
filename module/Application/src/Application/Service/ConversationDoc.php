<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Conversation Document
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class ConversationDoc.
 */
class ConversationDoc extends AbstractService
{
    /**
     * Add relation conversation document.
     *
     * @param int $conversation_id
     * @param int $library_id
     *
     * @return int
     */
    public function add($conversation_id, $library_id)
    {
        return $this->getMapper()->insert($this->getModel()
            ->setConversationId($conversation_id)
            ->setLibraryId($library_id));
    }

    /**
     * Delete relation conversation document.
     *
     * @param int $library_id
     */
    public function delete($library_id)
    {
        $res_conversation_doc = $this->getMapper()->select($this->getModel()
            ->setLibraryId($library_id));

        foreach ($res_conversation_doc as $m_conversation_doc) {
            $this->getMapper()->delete($this->getModel()
                ->setLibraryId($m_conversation_doc->getLibraryId()));

            $this->getServiceLibrary()->delete($m_conversation_doc->getLibraryId());
        }
    }

    /**
     * Get Service Library.
     *
     * @return \Application\Service\Library
     */
    private function getServiceLibrary()
    {
        return $this->container->get('app_service_library');
    }
}
