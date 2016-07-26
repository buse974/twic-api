<?php
/**
 * 
 * TheStudnet (http://thestudnet.com)
 *
 * Conversation Text Editor
 *
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class ConversationTextEditor
 */
class ConversationTextEditor extends AbstractService
{

    /**
     * Add Text Editor
     *
     * @param int $conversation_id            
     * @param int $text_editor_id            
     * @return int
     */
    public function add($conversation_id, $text_editor_id)
    {
        return $this->getMapper()->insert($this->getModel()
            ->setConversationId($conversation_id)
            ->setTextEditorId($text_editor_id));
    }

    /**
     * Delete Text Editor
     *
     * @param int $text_editor_id            
     */
    public function delete($text_editor_id)
    {
        $res_conversation_text_editor = $this->getMapper()->select($this->getModel()
            ->setTextEditorId($text_editor_id));
        
        foreach ($res_conversation_text_editor as $m_conversation_text_editor) {
            $this->getMapper()->delete($this->getModel()
                ->setTextEditorId($m_conversation_text_editor->getTextEditorId()));
            
            $this->getServiceTextEditor()->delete($m_conversation_text_editor->getTextEditorId());
        }
    }

    /**
     * Get Service TextEditor
     *
     * @return \Application\Service\TextEditor
     */
    private function getServiceTextEditor()
    {
        return $this->getServiceLocator()->get('app_service_text_editor');
    }
}   