<?php
/**
 * 
 * TheStudnet (http://thestudnet.com)
 *
 * Conversation
 *
 */
namespace Application\Service;

use Dal\Service\AbstractService;
use Application\Model\Conversation as ModelConversation;
use Application\Model\Item as ModelItem;
use OpenTok\Role as OpenTokRole;
use Application\Model\Role as ModelRole;
use Zend\Db\Sql\Predicate\IsNull;

/**
 * Class Conversation
 */
class Conversation extends AbstractService
{

    /**
     * Create New Conversation
     *
     * @invokable
     *
     * @param int $type            
     * @param int $submission_id            
     * @param array $users            
     * @param string $text            
     * @param int $item_id            
     * @param array $text_editors            
     * @param array $whiteboards            
     * @param array $documents            
     * @param bool $has_video            
     * @throws \Exception
     * @return int
     */
    public function create($type = null, $submission_id = null, $users = null, $text = null, $item_id = null, $text_editors = null, $whiteboards = null, $documents = null, $has_video = null)
    {
        $start_date = null;
        if (null === $submission_id && null !== $item_id) {
            $submission_id = $this->getServiceSubmission()
                ->getByItem($item_id)
                ->getId();
        }
        if (null !== $submission_id && null === $item_id) {
            $item_id = $this->getServiceSubmission()
            ->getBySubmission($submission_id)->getItemId();
        }
        
        $conversation_opt_id = null;
        if($item_id) {
            $m_conversation_opt = $this->getServiceConversationOpt()->getByItem($item_id);
            if(null !== $m_conversation_opt) {
                $conversation_opt_id = $m_conversation_opt->getId();
            }
        }
        if($conversation_opt_id === null) {
            $conversation_opt_id = $this->getServiceConversationOpt()->add();
        }
        $m_conversation = $this->getModel()
            ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'))
            ->setConversationOptId($conversation_opt_id)
            ->setType($type);
        
        if ($this->getMapper()->insert($m_conversation) <= 0) {
            throw new \Exception('Error create conversation');
        }
        $conversation_id = $this->getMapper()->getLastInsertValue();
        
        if ($has_video === true) {
            $this->addVideo($conversation_id);
        }
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
        if (null !== $text) {
            switch ($type) {
                case ModelConversation::TYPE_ITEM_GROUP_ASSIGNMENT:
                    $this->getServiceMessage()->sendSubmission($text, null, $conversation_id);
                    break;
                default:
                    $this->getServiceMessage()->send($text, null, $conversation_id);
            }
        }
        
        return $conversation_id;
    }

    /**
     * Add video Token in conversaton if not exist
     *
     * @invokable
     *
     * @param int $id            
     * @return int
     */
    public function addVideo($id)
    {
        $m_conversation = $this->getMapper()
            ->select($this->getModel()
            ->setId($id))
            ->current();
        
        $token = $m_conversation->getToken();
        return ($token === null || $token instanceof IsNull) ? $this->getMapper()->update($this->getModel()
            ->setToken($this->getServiceZOpenTok()
            ->getSessionId()), ['id' => $id]) : 0;
    }

    /**
     * Get conversation
     *
     * Get conversation whith all composant,
     * if $has_video true add Video if not exist.
     *
     * @invokable
     *
     * @param int $id            
     * @param string $has_video            
     * @return array
     */
    public function get($id, $has_video = false)
    {
        if ($has_video === true) {
            $this->addVideo($id);
        }
        
        $conv = $this->_get($id)->toArray();
        
        $editors = $this->getServiceTextEditor()->getListByConversation($id);
        if ((! is_array($editors) && $editors->count() > 0) || (is_array($editors) && ! empty($editors))) {
            $conv['editors'] = $editors;
        }
        
        $whiteboards = $this->getServiceWhiteboard()->getListByConversation($id);
        if ((! is_array($whiteboards) && $whiteboards->count() > 0) || (is_array($whiteboards) && ! empty($whiteboards))) {
            $conv['whiteboards'] = $whiteboards;
        }
        
        $documents = $this->getServiceLibrary()->getListByConversation($id);
        if ((! is_array($documents) && $documents->count() > 0) || (is_array($documents) && ! empty($documents))) {
            $conv['documents'] = $documents;
        }
        
        if(is_numeric($conv['conversation_opt_id'])) {
            $conversation_opt = $this->getServiceConversationOpt()->get($conv['conversation_opt_id']);
            if (null !== $conversation_opt) {
                $conv['conversation_opt'] = $conversation_opt;
            }
        }
        
        $conv['id'] = $id;
        $identity = $this->getServiceUser()->getIdentity();
        
        $m_submission = $this->getServiceSubmission()->getByUserAndConversation($identity['id'], $id);
        if (null !== $m_submission) {
            $conv['submission_id'] = $m_submission->getId();
        }
        
        if (isset($conv['token']) && ! empty($conv['token'])) {
            $conv['user_token'] = $this->getServiceZOpenTok()->createToken($conv['token'], '{"id":' . $identity['id'] . '}', (! array_key_exists(ModelRole::ROLE_STUDENT_ID, $identity['roles'])) ? OpenTokRole::MODERATOR : OpenTokRole::PUBLISHER);
        }
        
        return $conv;
    }

    /**
     * Get Conversation Lite Version
     *
     * @param int $id   
     * @return \Application\Model\Conversation
     */
    public function getLite($id)
    {
        return $this->getMapper()
            ->select($this->getModel()
            ->setId($id))
            ->current();
    }

    /**
     * Get List of conversation id
     *
     * Get List of conversation id, By program, course and item id
     * of current user school
     *
     * @invokable
     *
     * @param int $program_id            
     * @param int $course_id            
     * @param int $item_id            
     * @return array
     */
    public function getListId($program_id = null, $course_id = null, $item_id = null)
    {
        $identity = $this->getServiceUser()->getIdentity();
        $res_videoconf = $this->getMapper()->getListId($identity['school']['id'], $program_id, $course_id, $item_id);
        $ids = [];
        foreach ($res_videoconf as $m_videoconf) {
            $ids[] = $m_videoconf->getId();
        }
        
        return $ids;
    }

    /**
     * Get conversation id
     *
     * Get conversation id, By submission and/or item id
     * of current user school
     *
     * @invokable
     *
     * @param integer $submission_id            
     * @param integer $item_id            
     * @return int
     */
    public function getId($submission_id = null, $item_id = null)
    {
        if (null === $item_id && null === $submission_id) {
            return null;
        }
        
        $identity = $this->getServiceUser()->getIdentity();
        return $this->getMapper()
            ->getListId($identity['school']['id'], null, null, $item_id, $submission_id)
            ->current()
            ->getId();
    }

    /**
     * Remove Text Editor of conversation
     *
     * @invokable
     *
     * @param int $text_editor            
     * @return int
     */
    public function removeTextEditor($text_editor)
    {
        $this->getServiceConversationTextEditor()->delete($text_editor);
    }

    /**
     * Remove Whiteboard of conversation
     *
     * @invokable
     *
     * @param int $whiteboard            
     * @return int
     */
    public function removeWhiteboard($whiteboard)
    {
        $this->getServiceConversationWhiteboard()->delete($whiteboard);
    }

    /**
     * Remove Document of conversation
     *
     * @invokable
     *
     * @param int $document            
     * @return int
     */
    public function removeDocument($document)
    {
        $this->getServiceConversationDoc()->delete($document);
    }

    /**
     * Add Text Editor in conversation
     *
     * @invokable
     *
     * @param int $conversation            
     * @param array $text_editors            
     */
    public function addTextEditor($conversation, $text_editors)
    {
        if (! is_array($text_editors) || isset($text_editors['name'])) {
            $text_editors = [$text_editors];
        }
        
        foreach ($text_editors as $text_editor) {
            if (isset($text_editor['name'])) {
                $text_editor = $this->getServiceTextEditor()->_add($text_editor);
            }
            
            if (is_numeric($text_editor)) {
                $this->getServiceConversationTextEditor()->add($conversation, $text_editor);
            }
        }
    }

    /**
     * Add Whiteboard in conversation
     *
     * @invokable
     *
     * @param int $conversation            
     * @param array $text_editors            
     */
    public function addWhiteboard($conversation, $whiteboards)
    {
        if (! is_array($whiteboards) || isset($whiteboards['name'])) {
            $whiteboards = [$whiteboards];
        }
        
        foreach ($whiteboards as $whiteboard) {
            if (isset($whiteboard['name'])) {
                $whiteboard = $this->getServiceWhiteboard()->_add($whiteboard);
            }
            
            if (is_numeric($whiteboard)) {
                $this->getServiceConversationWhiteboard()->add($conversation, $whiteboard);
            }
        }
    }

    /**
     * Add Document in conversation
     *
     * @invokable
     *
     * @param int $conversation            
     * @param array $documents            
     */
    public function addDocument($conversation, $documents)
    {
        if (! is_array($documents) || isset($documents['name'])) {
            $documents = [$documents];
        }
        
        foreach ($documents as $document) {
            if (isset($document['name'])) {
                $m_library = $this->getServiceLibrary()->_add($document);
                $document = $m_library->getId();
            }
            
            if (is_numeric($document)) {
                $this->getServiceConversationDoc()->add($conversation, $document);
            }
        }
    }

    /**
     * @invokable
     *
     * @param array $users            
     * @param string $text            
     * @param int $submission            
     */
    public function createSubmission($users, $text, $submission)
    {
        $user_id = $this->getServiceUser()->getIdentity()['id'];
        if (! in_array($user_id, $users)) {
            $users[] = $user_id;
        }
        
        return $this->create(ModelConversation::TYPE_ITEM_GROUP_ASSIGNMENT, $submission, $users, $text);
    }

    /**
     * Create conversation.
     *
     * @invokable
     *
     * @param int|array $users            
     * @return int
     */
    public function add($users)
    {
        $user_id = $this->getServiceUser()->getIdentity()['id'];
        if (! in_array($user_id, $users)) {
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
     * @return array
     */
    public function join($conversation)
    {
        return $this->getServiceConversationUser()->add($conversation, $this->getServiceUser()
            ->getIdentity()['id']);
    }

    /**
     * Basique get conversation
     *
     * @param integer $id            
     * @return \Application\Model\Conversation
     */
    public function _get($id)
    {
        $conv = $this->getMapper()
            ->select($this->getModel()
            ->setId($id))
            ->current();
        $conv->setMessages($this->getServiceMessage()
            ->getList($id, []));
        $conv->setUsers($this->getServiceUser()
            ->getListByConversation($id)
            ->toArray(array('id')));
        
        return $conv;
    }

    /**
     * Get List Conversation By Submission
     *
     * @param int $submission_id            
     * @param bool $all Si false on teste pour tous le monde sinon on filtre l'utilisateur courant       
     * @return array
     */
    public function getListBySubmission($submission_id, $all = false)
    {
        $user_id = (false === $all) ? 
            $this->getServiceUser()->getIdentity()['id'] : 
            null;
        
        $res_conversation = $this->getMapper()->getListBySubmission($submission_id, $user_id);
        $ret = [];
        foreach ($res_conversation as $m_conversation) {
            $ret[] = $this->get($m_conversation->getId()) + $m_conversation->toArray();
        }
        
        return $ret;
    }

    /**
     * Get List Conversation By Item
     * 
     * @param int $item_id            
     * @param int $submission_id            
     * @return array
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
     * Get List Conversation Or Create If not exist
     * 
     * @param int $submission_id            
     * @return array
     */
    public function getListOrCreate($submission_id)
    {
        $m_item = $this->getServiceItem()->getBySubmission($submission_id);
        
        // Dans le cas d'un type chat sans set_id il faut tt créer par item
        $by_item = ($m_item->getType() === ModelItem::TYPE_CHAT && ! $m_item->getIsGrouped());
        $ar = ($by_item) ? $this->getListByItem($m_item->getId()) : $this->getListBySubmission($submission_id, true);
        
        if (count($ar) <= 0) {
            $m_submission = $this->getServiceSubmission()->getBySubmission($submission_id);
            $res_user = ($by_item) ? $this->getServiceUser()->getListByItem($m_item->getId()) : $this->getServiceUser()->getListUsersBySubmission($submission_id);
            
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
     * Mark Read Message(s).
     *
     * @invokable
     * 
     * @param int|array $conversation
     * @return int
     */
    public function read($conversation)
    {
        return $this->getServiceMessageUser()->readByConversation($conversation);
    }

    /**
     * Mark UnRead Message(s).
     *
     * @invokable
     *
     * @param int|array $conversation
     * @return int       
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
     * Get Service Service Conversation User
     *
     * @return \Application\Service\ConversationUser
     */
    private function getServiceConversationUser()
    {
        return $this->getServiceLocator()->get('app_service_conversation_user');
    }

    /**
     * Get Service Service Submission Conversation
     *
     * @return \Application\Service\SubConversation
     */
    private function getServiceSubConversation()
    {
        return $this->getServiceLocator()->get('app_service_sub_conversation');
    }

    /**
     * Get Service Service User
     *
     * @return \Application\Service\User
     */
    private function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }

    /**
     * Get Service Service Item
     *
     * @return \Application\Service\Item
     */
    private function getServiceItem()
    {
        return $this->getServiceLocator()->get('app_service_item');
    }

    /**
     * Get Service Service Message User
     *
     * @return \Application\Service\MessageUser
     */
    private function getServiceMessageUser()
    {
        return $this->getServiceLocator()->get('app_service_message_user');
    }

    /**
     * Get Service Service Submission
     *
     * @return \Application\Service\Submission
     */
    private function getServiceSubmission()
    {
        return $this->getServiceLocator()->get('app_service_submission');
    }

    /**
     * Get Service Service Library
     *
     * @return \Application\Service\Library
     */
    private function getServiceLibrary()
    {
        return $this->getServiceLocator()->get('app_service_library');
    }

    /**
     * Get Service Service Conversation option
     *
     * @return \Application\Service\ConversationOpt
     */
    private function getServiceConversationOpt()
    {
        return $this->getServiceLocator()->get('app_service_conversation_opt');
    }

    /**
     * Get Service Service Conversation Document
     *
     * @return \Application\Service\ConversationDoc
     */
    private function getServiceConversationDoc()
    {
        return $this->getServiceLocator()->get('app_service_conversation_doc');
    }

    /**
     * Get Service Service Conversation TextEditor
     *
     * @return \Application\Service\ConversationTextEditor
     */
    private function getServiceConversationTextEditor()
    {
        return $this->getServiceLocator()->get('app_service_conversation_text_editor');
    }

    /**
     * Get Service Service Message
     *
     * @return \Application\Service\Message
     */
    private function getServiceMessage()
    {
        return $this->getServiceLocator()->get('app_service_message');
    }

    /**
     * Get Service Service TextEditor
     *
     * @return \Application\Service\TextEditor
     */
    private function getServiceTextEditor()
    {
        return $this->getServiceLocator()->get('app_service_text_editor');
    }

    /**
     * Get Service Service Conversation Whiteboard
     *
     * @return \Application\Service\ConversationWhiteboard
     */
    private function getServiceConversationWhiteboard()
    {
        return $this->getServiceLocator()->get('app_service_conversation_whiteboard');
    }

    /**
     * Get Service Service Whiteboard
     *
     * @return \Application\Service\Whiteboard
     */
    private function getServiceWhiteboard()
    {
        return $this->getServiceLocator()->get('app_service_whiteboard');
    }

    /**
     * Get Service Service OpenTok
     *
     * @return \ZOpenTok\Service\OpenTok
     */
    private function getServiceZOpenTok()
    {
        return $this->getServiceLocator()->get('opentok.service');
    }
}
