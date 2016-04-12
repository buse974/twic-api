<?php

namespace Application\Service;

use Dal\Service\AbstractService;
use Application\Model\Item as ModelItem;

class Submission extends AbstractService
{
    public function getByUserAndQuestionnaire($me, $questionnaire)
    {
        $m_submission = $this->getMapper()->getByUserAndQuestionnaire($me, $questionnaire);
    }
    
    public function getList($item_id)
    {
        $ret = [];
        $m_item = $this->getServiceItem()->get($item_id);
        
        switch ($m_item->getType()) {
            case ModelItem::TYPE_CHAT :
                $ret[ModelItem::CMP_CHAT] = $this->getServiceConversation()->get($item_id);
                $ret[ModelItem::CMP_ASSIGNMENT] = $this->getServiceConversation()->get($item_id);
                $ret[ModelItem::CMP_DISCUSSION] = $this->getServiceConversation()->get($item_id);
                $ret[ModelItem::CMP_DOCUMENT] = $this->getServiceConversation()->get($item_id);
                $ret[ModelItem::CMP_EQCQ] = $this->getServiceConversation()->get($item_id);
                $ret[ModelItem::CMP_POLL] = $this->getServiceConversation()->get($item_id);
                $ret[ModelItem::CMP_VIDEOCONF] = $this->getServiceConversation()->get($item_id);
            break;
        }
        
        $ret[ModelItem::CMP_ASSIGNMENT] = $this->getServiceConversation()->get($item_id);
        $ret[ModelItem::CMP_TEXT_EDITOR] = $this->getServiceConversation()->get($item_id);
    }
    
    /**
     * 
     * @return \Application\Service\Document
     */
    public function getServiceDocument()
    {
        return $this->getServiceLocator()->get('app_service_document');
    }
    
    /**
     *
     * @return \Application\Service\TextEditor
     */
    public function getServiceTextEditor()
    {
        return $this->getServiceLocator()->get('app_service_text_editor');
    }
    
    /**
     *
     * @return \Application\Service\Conversation
     */
    public function getServiceConversation()
    {
        return $this->getServiceLocator()->get('app_service_conversation');
    }
    
    /**
     *
     * @return \Application\Service\Item
     */
    public function getServiceItem()
    {
        return $this->getServiceLocator()->get('app_service_item');
    }
}