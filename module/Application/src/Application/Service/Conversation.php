<?php

namespace Application\Service;

use Dal\Service\AbstractService;
use Application\Model\Conversation as ModelConversation;
use Application\Model\Item as ModelItem;

class Conversation extends AbstractService
{
    /**
     * 
     * @invokable
     * 
     * Create Conversation.
     * 
     * @param int    $type
     * @param int    $submission_id
     * @param array  $users
     * @param string $text
     * @param int    $item_id
     * @param array  $text_editors
     * @param array  $whiteboards
     * @param array  $documents
     * @param boolean $has_video
     */
    public function create(
        $type = null, 
        $submission_id = null,  
        $users = null, 
        $text = null, 
        $item_id = null, 
        $text_editors = null, 
        $whiteboards = null, 
        $documents = null, 
        $has_video = null,
        $conversation = null)
    {
        $start_date = null;
        if (null === $submission_id && null !== $item_id) { 
            $submission_id = $this->getServiceSubmission()->getByItem($item_id)->getId(); 
        } 

        $m_conversation = $this->getModel() 
            ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s')) 
            ->setType($type); 

        if ($has_video === true) {
            $m_conversation->setToken($this->getServiceZOpenTok()->getSessionId()); 
        }
        if ($this->getMapper()->insert($m_conversation) <= 0) { 
            throw new \Exception('Error create conversation'); 
        }
        $conversation_id = $this->getMapper()->getLastInsertValue();
        
        if (null !== $users) {
            $this->getServiceConversationUser()->add($conversation_id, $users);
        }
        if (null !== $submission_id) {
            $this->getServiceSubConversation()->add($conversation_id, $submission_id);
        }
        if (null !== $text_editors) {
            $this->addTextEditor($conversation_id, $text_editors);
        }
        if (null !== $whiteboards) {
            $this->addWhiteboard($conversation_id, $whiteboards);
        }
        if (null !== $documents) {
            $this->addDocument($conversation_id, $documents);
        }
        if (null !== $conversation) {
            $this->addConversation($conversation_id, $conversation);
        }
        if (null !== $text) {
            switch ($type) {
                case ModelConversation::TYPE_ITEM_GROUP_ASSIGNMENT :
                    $this->getServiceMessage()->sendSubmission($text, null, $conversation_id);
                    break;
                default :
                    $this->getServiceMessage()->send($text, null, $conversation_id);
            }
        }

        return $conversation_id;
    }

    /**
     * @invokable
     *
     * @param integer $conversation_id
     * @param array $text_editors
     */
    public function addTextEditor($conversation_id, $text_editors)
    {
        if(!is_array($text_editors) || isset($text_editors['name'])) {
            $text_editors = [$text_editors];
        }
    
        foreach ($text_editors as $text_editor) {
            if(isset($text_editor['name'])) {
                $m_library = $this->getServiceTextEditor()->_add($text_editor);
                $text_editor = $m_library->getId();
            }
    
            if(is_numeric($text_editor)) {
                $this->getServiceConversationTextEditor()->add($conversation_id, $text_editor);
            }
        }
    }
    
    /**
     * @invokable
     *
     * @param integer $conversation_id
     * @param array $text_editors
     */
    public function addWhiteboard($conversation_id, $whiteboards)
    {
        if(!is_array($whiteboards) || isset($whiteboards['name'])) {
            $whiteboards = [$whiteboards];
        }
    
        foreach ($whiteboards as $whiteboard) {
            if(isset($whiteboard['name'])) {
                $m_library = $this->getServiceWhiteboard()->_add($whiteboard);
                $whiteboard = $m_library->getId();
            }
    
            if(is_numeric($whiteboard)) {
                $this->getServiceConversationWhiteboard()->add($conversation_id, $whiteboard);
            }
        }
    }
    
    /**
     * @invokable
     * 
     * @param integer $conversation_id
     * @param array $documents
     */
    public function addDocument($conversation_id, $documents)
    {
        if(!is_array($documents) || isset($documents['name'])) {
            $documents = [$documents];
        }
        
        foreach ($documents as $document) {
            if(isset($document['name'])) { 
                $m_library = $this->getServiceLibrary()->_add($document);
                $document = $m_library->getId();
            } 
            
            if(is_numeric($document)) {
            $this->getServiceConversationDoc()->add($conversation_id, $document);
            }
        }
    }
    
    /**
     * @invokable
     * 
     * @param integer $id
     * @param integer $conversation_id
     * 
     * @return integer
     */
    public function addConversation($id, $conversation_id)
    {
        return $this->getServiceConversationConversation()->add($id, $conversation_id);
    }
    
    /**
     * @invokable
     * 
     * @param array  $users
     * @param string $text
     * @param int    $submission
     */
    public function createSubmission($users, $text, $submission)
    {
        $user_id = $this->getServiceUser()->getIdentity()['id'];
        if (!in_array($user_id, $users)) {
            $users[] = $user_id;
        }

        return $this->create(ModelConversation::TYPE_ITEM_GROUP_ASSIGNMENT, $submission, $users, $text);
    }

    /**
     * Create conversation.
     *
     * @invokable
     *
     * @param array $users
     *
     * @return int
     */
    public function add($users)
    {
        $user_id = $this->getServiceUser()->getIdentity()['id'];
        if (!in_array($user_id, $users)) {
            $users[] = $user_id;
        }

        return $this->create(null, null, $users);
    }

    /**
     * Joindre conversation.
     *
     * @invokable
     *
     * @param int $conversation
     */
    public function join($conversation)
    {
        return $this->getServiceConversationUser()->add($conversation, $this->getServiceUser()->getIdentity()['id']);
    }

    /**
     * @invokable
     *
     * @param int $conversation
     */
    public function get($id, $filter = [])
    {
        $conv['users'] = $this->getServiceUser()->getListByConversation($id)->toArray(array('id'));
        $conv['messages'] = $this->getServiceMessage()->getList($id, $filter);
        $conv['conversations'] = $this->getListConversationByConversation($id);
        $conv['id'] = $id;
        

        return $conv;
    }
    
    public function _get($id)
    {
        $conv = $this->getMapper()->select($this->getModel()->setId($id))->current();
        $conv->setMessages($this->getServiceMessage()->getList($id, []));
        
        return $conv;
    }
    
    public function getListConversationByConversation($id)
    {
        $res_conversation_conversation = $this->getServiceConversationConversation()->getList($id);
        
        $ret = [];
        foreach ($res_conversation_conversation as $m_conversation_conversation) {
            $ret[] = $this->_get($m_conversation_conversation->getConversationId());
        }
        
        return $ret;
    }

    /**
     * @param int  $submission_id
     * @param bool $all           Si false on teste pour tous le monde sinon on filtre l'utilisateur courant
     * 
     * @return []
     */
    public function getListBySubmission($submission_id, $all = false)
    {
        $user_id = (true === $all) ? null : $this->getServiceUser()->getIdentity()['id'];
        $res_conversation = $this->getMapper()->getListBySubmission($submission_id, $user_id);
        $ret = [];
        foreach ($res_conversation as $m_conversation) {
            $ret[] = $this->get($m_conversation->getId()) + $m_conversation->toArray();
        }

        return $ret;
    }

    /**
     * @param int $item_id
     * @param int $submission_id
     * 
     * @return []
     */
    public function getListByItem($item_id, $submission_id = null)
    {
        $res_conversation = $this->getMapper()->getListByItem($item_id, $submission_id);
        $ret = [];
        foreach ($res_conversation as $m_conversation) {
            $ret[] = $this->get($m_conversation->getId()) + $m_conversation->toArray();
        }

        return $ret;
    }

    /**
     * @param int $submission_id
     * 
     * @return []
     */
    public function getListOrCreate($submission_id)
    {
        $m_item = $this->getServiceItem()->getBySubmission($submission_id);

        // Dans le cas d'un type chat sans set_id il faut tt créer par item
        $by_item = ($m_item->getType() === ModelItem::TYPE_CHAT && !$m_item->getIsGrouped());
        $ar = ($by_item) ?
            $this->getListByItem($m_item->getId()) :
            $this->getListBySubmission($submission_id, true);

        if (count($ar) <= 0) {
            $m_submission = $this->getServiceSubmission()->getBySubmission($submission_id);
            $res_user = ($by_item) ?
                $this->getServiceUser()->getListByItem($m_item->getId()) :
                $this->getServiceUser()->getListUsersBySubmission($submission_id);

            $users = [];
            foreach ($res_user as $m_user) {
                $users[] = $m_user->getId();
            }
            $this->create(ModelConversation::TYPE_ITEM_GROUP_ASSIGNMENT, $submission_id, $users);
        }

        if ($by_item) {
            // Vérifier si la conversation est linker sur la submission par un byItem
            $res = $this->getListByItem($m_item->getId(), $submission_id);
            if (count($res) <= 0) {
                if (count($ar) <= 0) {
                    $ar = $this->getListByItem($m_item->getId());
                }
                $conv = current($ar);
                $this->getServiceSubConversation()->add($conv['id'], $submission_id);
            }
        }

        return $this->getListBySubmission($submission_id);
    }

    /**
     * Read Message(s).
     *
     * @invokable
     *
     * @param int|array $conversation
     */
    public function read($conversation)
    {
        return $this->getServiceMessageUser()->readByConversation($conversation);
    }

    /**
     * UnRead Message(s).
     *
     * @invokable
     *
     * @param int|array $conversation
     */
    public function unRead($conversation)
    {
        return $this->getServiceMessageUser()->unReadByConversation($conversation);
    }

    /**
     * Delete Message(s).
     *
     * @invokable
     *
     * @param int|array $conversation
     */
    public function delete($conversation)
    {
        return $this->getServiceMessageUser()->deleteByConversation($conversation);
    }

    /**
     * @return \Application\Service\ConversationUser
     */
    public function getServiceConversationUser()
    {
        return $this->getServiceLocator()->get('app_service_conversation_user');
    }

    /**
     * @return \Application\Service\SubConversation
     */
    public function getServiceSubConversation()
    {
        return $this->getServiceLocator()->get('app_service_sub_conversation');
    }

    /**
     * @return \Application\Service\User
     */
    public function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }

    /**
     * @return \Application\Service\Item
     */
    public function getServiceItem()
    {
        return $this->getServiceLocator()->get('app_service_item');
    }

    /**
     * @return \Application\Service\MessageUser
     */
    public function getServiceMessageUser()
    {
        return $this->getServiceLocator()->get('app_service_message_user');
    }

    /**
     * @return \Application\Service\Submission
     */
    public function getServiceSubmission()
    {
        return $this->getServiceLocator()->get('app_service_submission');
    }
    
    /**
     * @return \Application\Service\Library
     */
    public function getServiceLibrary()
    {
        return $this->getServiceLocator()->get('app_service_library');
    }
    
    /**
     * @return \Application\Service\ConversationConversation
     */
    public function getServiceConversationConversation()
    {
        return $this->getServiceLocator()->get('app_service_conversation_conversation');
    }
    
    /**
     * @return \Application\Service\ConversationDoc
     */
    public function getServiceConversationDoc()
    {
        return $this->getServiceLocator()->get('app_service_conversation_doc');
    }
    
    /**
     * @return \Application\Service\ConversationTextEditor
     */
    public function getServiceConversationTextEditor()
    {
        return $this->getServiceLocator()->get('app_service_conversation_text_editor');
    }
    
    /**
     * @return \Application\Service\Message
     */
    public function getServiceMessage()
    {
        return $this->getServiceLocator()->get('app_service_message');
    }
    
    /**
     * @return \Application\Service\TextEditor
     */
    public function getServiceTextEditor()
    {
        return $this->getServiceLocator()->get('app_service_text_editor');
    }
    
    /**
     * @return \Application\Service\ConversationWhiteboard
     */
    public function getServiceConversationWhiteboard()
    {
        return $this->getServiceLocator()->get('app_service_conversation_whiteboard');
    }
    
    /**
     * @return \Application\Service\Whiteboard
     */
    public function getServiceWhiteboard()
    {
        return $this->getServiceLocator()->get('app_service_whiteboard');
    }
    
    /**
     * @return \ZOpenTok\Service\OpenTok
     */
    public function getServiceZOpenTok()
    {
        return $this->getServiceLocator()->get('opentok.service');
    }
}
