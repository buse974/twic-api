<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Conversation
 */
namespace Application\Service;

use Dal\Service\AbstractService;
use Application\Model\Conversation as ModelConversation;
use Application\Model\Item as ModelItem;
use OpenTok\Role as OpenTokRole;
use Application\Model\Role as ModelRole;
use Zend\Db\Sql\Predicate\IsNull;
use JRpc\Json\Server\Exception\JrpcException;
use Application\Model\Library as ModelLibrary;
use OpenTok\MediaMode;

/**
 * Class Conversation.
 */
class Conversation extends AbstractService
{

    /**
     * Create New Conversation
     *
     * @invokable
     *
     * @param int $type            
     * @param int|array $submission_id            
     * @param array $users            
     * @param string $text            
     * @param int $item_id            
     * @param array $text_editors            
     * @param array $whiteboards            
     * @param array $documents            
     * @param bool $has_video            
     *
     * @throws \Exception
     *
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
                ->getBySubmission(is_array($submission_id) ? reset($submission_id) : $submission_id)
                ->getItemId();
        }
        
        $conversation_opt_id = null;
        if ($item_id) {
            $m_conversation_opt = $this->getServiceConversationOpt()->getByItem($item_id);
            if (null !== $m_conversation_opt) {
                $conversation_opt_id = $m_conversation_opt->getId();
            }
        }
        if ($conversation_opt_id === null) {
            $conversation_opt_id = ($type === ModelConversation::TYPE_CHAT) ?
                 $this->getServiceConversationOpt()->add(null, 0, 0, 0, 0, null) :
                 $this->getServiceConversationOpt()->add();
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
            $r = $this->getServiceConversationUser()->add($conversation_id, $users);
        }
        if (null !== $submission_id) {
            if (! is_array($submission_id)) {
                $submission_id = [$submission_id];
            }
            foreach ($submission_id as $s) {
                $this->getServiceSubConversation()->add($conversation_id, $s);
                $res_sub_text_editor = $this->getServiceSubTextEditor()->getList($s);
                foreach ($res_sub_text_editor as $m_sub_text_editor) {
                    $text_editors[] = $m_sub_text_editor->getTextEditorId();
                }
                $res_sub_whiteboard = $this->getServiceSubWhiteboard()->getList($s);
                foreach ($res_sub_whiteboard as $m_sub_whiteboard) {
                    $whiteboards[] = $m_sub_whiteboard->getWhiteboardId();
                }
                $res_sub_document = $this->getServiceDocument()->getListBySubmission($s);
                foreach ($res_sub_document as $m_sub_document) {
                    $documents[] = $m_sub_document->getLibraryId();
                }
            }
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
     * Add video Token in conversaton if not exist.
     *
     * @invokable
     *
     * @param int $id            
     *
     * @return int
     */
    public function addVideo($id)
    {
        $m_conversation = $this->getMapper()
            ->select($this->getModel()
            ->setId($id))
            ->current();
        
        $token = $m_conversation->getToken();
        $media_mode = $m_conversation->getType() === ModelConversation::TYPE_CHAT ? MediaMode::RELAYED : MediaMode::ROUTED;
                       
        return ($token === null || $token instanceof IsNull) ? 
            $this->getMapper()->update($this->getModel()->setToken($this->getServiceZOpenTok()
            ->getSessionId($media_mode)), ['id' => $id, new IsNull('token')]) : 0;
    }

    /**
     * Get conversation.
     *
     * Get conversation whith all composant,
     * if $has_video true add Video if not exist.
     *
     * @invokable
     *
     * @param int $id            
     * @param string $has_video            
     *
     * @return array
     */
    public function get($id, $has_video = false)
    {
        /*
         * @TODO Check que letudiant a le droit
         * Check que linstructeur et bien dans le cour
         */
        $has_joined = false;
        $identity = $this->getServiceUser()->getIdentity();
        if (in_array(ModelRole::ROLE_INSTRUCTOR_STR, $identity['roles']) || in_array(ModelRole::ROLE_ACADEMIC_STR, $identity['roles'])) {
            $res_user = $this->getServiceUser()->getListByConversation($id);
            $is_present = false;
            foreach ($res_user as $m_user) {
                if ($m_user->getId() === $identity['id']) {
                    $is_present = true;
                    break;
                }
            }
            if (! $is_present) {
                $this->getServiceConversationUser()->add($id, $identity['id']);
                $has_joined = true;
            }
        }
        if ($has_video === true) {
            $this->addVideo($id);
        }
        
        $conv = $this->_get($id)->toArray();
        
        if ($has_joined) {
            $conv['has_joined'] = true;
        }
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
        
        if (is_numeric($conv['conversation_opt_id'])) {
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
     * Get Conversation Lite Version.
     *
     * @param int $id            
     *
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
     * Get List of conversation id.
     *
     * Get List of conversation id, By program, course and item id
     * of current user school
     *
     * @invokable
     *
     * @param int $program_id            
     * @param int $course_id            
     * @param int $item_id            
     * @param int $organization_id            
     * @param array $users            
     * @return array
     */
    public function getListId($program_id = null, $course_id = null, $item_id = null, $organization_id = null, $users = null)
    {
        $identity = $this->getServiceUser()->getIdentity();
        
        if (null !== $organization_id) {
            if (! $this->getServiceUser()->checkOrg($organization_id)) {
                throw new JrpcException('unauthorized orgzanization: ' . $organization_id);
            }
        }
        
        $res_videoconf = $this->getMapper()->getListId($identity['id'], $organization_id, $program_id, $course_id, $item_id, null, $users);
        $ids = [];
        foreach ($res_videoconf as $m_videoconf) {
            $ids[] = $m_videoconf->getId();
        }
        
        return $ids;
    }

    /**
     * Get conversation id.
     *
     * Get conversation id, By submission and/or item id
     * of current user school If not exist create
     *
     * @invokable
     *
     * @param int $submission_id            
     * @param int $item_id            
     *
     * @return int
     */
    public function getId($submission_id = null, $item_id = null)
    {
        if (null === $item_id && null === $submission_id) {
            return;
        }
        
        $identity = $this->getServiceUser()->getIdentity();
        $user_id = $identity['id'];
        
        // la submission suffit pour récuperer la conv
        if (in_array(ModelRole::ROLE_STUDENT_STR, $identity['roles'])) {
            if (null !== $submission_id) {
                // on verifie que la submission et bien la sienne
                $re_submission_user = $this->getServiceSubmissionUser()->getListBySubmissionId($submission_id, $user_id);
                if ($re_submission_user->count() <= 0) {
                    throw new \Exception('submission ' . $submission_id . ' for user ' . $user_id . ' not exist');
                }
            } else {
                // on verifie et récupére la submisiion de létudiant
                // si il en a pas on le vire
                $m_submission = $this->getServiceSubmission()->getByItem($item_id, $user_id);
                if ($m_submission === null) {
                    throw new \Exception('item ' . $item_id . ' for user ' . $user_id . ' not exist');
                }
                $submission_id = $m_submission->getId();
            }
        } else {
            $m_item = ($item_id !== null) ? $this->getServiceItem()->get($item_id) : $this->getServiceItem()->getBySubmission($submission_id);
            if ($m_item->getIsGrouped() == 1) {
                if ($submission_id == null) {
                    throw new \Exception('error get id conversation for instructor');
                }
                $item_id = null;
            } else {
                if ($submission_id !== null && $item_id === null) {
                    $item_id = $m_item->getId();
                }
                $submission_id = null;
            }
        }
        
        $is_admin = in_array(ModelRole::ROLE_ACADEMIC_STR, $identity['roles']);
        $res_conversation = $this->getMapper()->getListId($user_id, null, null, null, $item_id, $submission_id, null, $is_admin);
        
        // Si pas de résultat
        // Soit lutilisateur na pas etait ajouter soit la conversation n'existe pas
        $id = null;
        if ($res_conversation->count() <= 0) {
            $res_submission_user = null;
            // arriver ici on a pour un etudiant forcement sa submission
            // on verifie si c un live class si il na pas etait ajouter
            if (in_array(ModelRole::ROLE_STUDENT_STR, $identity['roles'])) {
                $m_item = $this->getServiceItem()->getBySubmission($submission_id);
                // 0 liveclass
                if ($m_item->getIsGrouped() == 0) {
                    // il faut verifier ici si une liveclass exist
                    // on ce fait passer pour admin pour ne pas filter sur user_id car il ny est pas dans la conv
                    $res_conversation = $this->getMapper()->getListId($user_id, null, null, null, $m_item->getId(), null, null, true);
                    $res_submission_user = $this->getServiceSubmissionUser()->getListByItemId($m_item->getId());
                    $is_present = false;
                    foreach ($res_submission_user as $m_submission_user) {
                        if ($m_submission_user->getUserId() === $user_id) {
                            $is_present = true;
                            break;
                        }
                    }
                    if ($is_present === false) {
                        throw new \Exception('error get id no autorization');
                    }
                    
                    if ($res_conversation->count() <= 0) {
                        // si liveclass qui nexiste pas on recupére tt les user de l'item
                        $res_submission_user = $this->getServiceSubmissionUser()->getListByItemId($m_item->getId());
                    } else {
                        // Cas on lutilisateur a etait ajouter a la voler
                        $id = $res_conversation->current()->getId();
                        $this->getServiceSubConversation()->add($id, $submission_id);
                        $this->getServiceConversationUser()->add($id, $user_id);
                        
                        return $id;
                    }
                } else {
                    // si group w on récupere tt les user de la submission
                    $res_conversation = $this->getMapper()->getListId($user_id, null, null, null, null, $submission_id, null, true);
                    
                    if ($res_conversation->count() <= 0) {
                        $res_submission_user = $this->getServiceSubmissionUser()->getList($submission_id);
                    } else {
                        // Cas on lutilisateur a etait ajouter a la voler
                        $id = $res_conversation->current()->getId();
                        $this->getServiceSubConversation()->add($id, $submission_id);
                        $this->getServiceConversationUser()->add($id, $user_id);
                        
                        return $id;
                    }
                }
            } else 
                if (in_array(ModelRole::ROLE_INSTRUCTOR_STR, $identity['roles']) || in_array(ModelRole::ROLE_ACADEMIC_STR, $identity['roles'])) {
                    // si item donc forcement liveclass on récupere tt les user de litem
                    if (null !== $item_id) {
                        $res_submission_user = $this->getServiceSubmissionUser()->getListByItemId($item_id);
                    } else {
                        $res_submission_user = $this->getServiceSubmissionUser()->getList($submission_id);
                    }
                }
            
            if ($res_submission_user === null) {
                throw new \Exception('error submission is not exist');
            }
            
            $u = [];
            $s = [];
            foreach ($res_submission_user as $m_submission_user) {
                $u[] = $m_submission_user->getUserId();
                $s[] = $m_submission_user->getSubmissionId();
            }
            
            $id = $this->create(ModelConversation::TYPE_VIDEOCONF, array_unique($s), $u, null, $item_id, null, null, null, true);
        } else {
            $id = $res_conversation->current()->getId();
        }
        
        return $id;
    }

    /**
     * Create a Submission conversation.
     *
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
     * Create conversation with users
     *
     * @invokable
     *
     * @param int|array $users            
     *
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
     *
     * @return array
     */
    public function join($conversation)
    {
        return $this->getServiceConversationUser()->add($conversation, $this->getServiceUser()
            ->getIdentity()['id']);
    }

    /**
     * Basique get conversation.
     *
     * @param int $id            
     *
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
            ->toArray(['id']));
        
        return $conv;
    }

    /**
     * Get List Conversation By Submission.
     *
     * @param int $submission_id            
     * @param bool $all
     *            Si false on teste pour tous le monde sinon on filtre l'utilisateur courant
     *            
     * @return array
     */
    public function getListBySubmission($submission_id, $all = false)
    {
        $user_id = (false === $all) ? $this->getServiceUser()->getIdentity()['id'] : null;
        
        $res_conversation = $this->getMapper()->getListBySubmission($submission_id, $user_id);
        $ret = [];
        foreach ($res_conversation as $m_conversation) {
            $ret[] = $this->get($m_conversation->getId()) + $m_conversation->toArray();
        }
        
        return $ret;
    }

    /**
     * Get List Conversation By Item.
     *
     * @param int $item_id            
     * @param int $submission_id            
     *
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
     * Get List Conversation Or Create If not exist.
     *
     * @param int $submission_id            
     *
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
     * Get List Conversation 
     * 
     * @invokable
     * 
     * @param array $filter
     */
    public function getList($filter)
    {
        $conversation = [];
        $res_message_user = $this->getServiceMessageUser()->getListLastMessage($filter);
        foreach ($res_message_user as $m_message_user) {
            $m_conversation = $this->getLite($m_message_user->getConversationId());
            $m_conversation->setUsers($this->getServiceConversationUser()->getListUserIdByConversation($m_message_user->getConversationId()));
            $m_conversation->setMessageUser($m_message_user);
            
            $conversation[] = $m_conversation;
        }
        
        return $conversation;
    }

    /**
     * Add Text Editor in conversation.
     *
     * @invokable
     *
     * @param int $conversation            
     * @param array $text_editors            
     */
    public function addTextEditor($conversation, $text_editors)
    {
        $res_sub_conversation = $this->getServiceSubConversation()->getList($conversation);
        $is_multi = true;
        
        if (! is_array($text_editors) || isset($text_editors['name'])) {
            $text_editors = [$text_editors];
            $is_multi = false;
        }
        
        foreach ($text_editors as $text_editor) {
            if (isset($text_editor['name'])) {
                $text_editor = $this->getServiceTextEditor()->_add($text_editor);
            }
            $ret[] = $text_editor;
            if (is_numeric($text_editor)) {
                $this->getServiceConversationTextEditor()->add($conversation, $text_editor);
                foreach ($res_sub_conversation as $m_sub_conversation) {
                    $this->getServiceSubTextEditor()->add($m_sub_conversation->getSubmissionId(), $text_editor);
                }
            }
        }
        
        return ($is_multi) ? $ret : current($ret);
    }

    /**
     * Remove Text Editor of conversation.
     *
     * @invokable
     *
     * @param int $text_editor            
     *
     * @return int
     */
    public function removeTextEditor($text_editor)
    {
        $this->getServiceConversationTextEditor()->delete($text_editor);
    }

    /**
     * Remove Whiteboard of conversation.
     *
     * @invokable
     *
     * @param int $whiteboard            
     *
     * @return int
     */
    public function removeWhiteboard($whiteboard)
    {
        $this->getServiceConversationWhiteboard()->delete($whiteboard);
    }

    /**
     * Remove Document of conversation.
     *
     * @invokable
     *
     * @param int $document            
     *
     * @return int
     */
    public function removeDocument($document)
    {
        $this->getServiceConversationDoc()->delete($document);
    }

    /**
     * Add Whiteboard in conversation.
     *
     * @invokable
     *
     * @param int $conversation            
     * @param array $text_editors            
     */
    public function addWhiteboard($conversation, $whiteboards)
    {
        $res_sub_conversation = $this->getServiceSubConversation()->getList($conversation);
        $is_multi = true;
        
        if (! is_array($whiteboards) || isset($whiteboards['name'])) {
            $whiteboards = [$whiteboards];
            $is_multi = false;
        }
        
        foreach ($whiteboards as $whiteboard) {
            if (isset($whiteboard['name'])) {
                $whiteboard = $this->getServiceWhiteboard()->_add($whiteboard);
            }
            $ret[] = $whiteboard;
            if (is_numeric($whiteboard)) {
                $this->getServiceConversationWhiteboard()->add($conversation, $whiteboard);
                foreach ($res_sub_conversation as $m_sub_conversation) {
                    $this->getServiceSubWhiteboard()->add($m_sub_conversation->getSubmissionId(), $whiteboard);
                }
            }
        }
        
        return ($is_multi) ? $ret : current($ret);
    }

    /**
     * Add Document in conversation.
     *
     * @invokable
     *
     * @param int $conversation            
     * @param array $documents            
     *
     * @return array|int
     */
    public function addDocument($conversation, $documents)
    {
        $res_sub_conversation = $this->getServiceSubConversation()->getList($conversation);
        $is_multi = true;
        
        if (! is_array($documents) || isset($documents['name'])) {
            $documents = [$documents];
            $is_multi = false;
        }
        
        $ret = [];
        foreach ($documents as $document) {
            if (isset($document['name'])) {
                $document['folder_id'] = ModelLibrary::FOLDER_OTHER_INT;
                $m_library = $this->getServiceLibrary()->_add($document);
                $document = $m_library->getId();
            }
            $ret[] = $document;
            if (is_numeric($document)) {
                $this->getServiceConversationDoc()->add($conversation, $document);
                foreach ($res_sub_conversation as $m_sub_conversation) {
                    $this->getServiceDocument()->addRelation($m_sub_conversation->getSubmissionId(), $document);
                }
            }
        }
        
        return ($is_multi) ? $ret : current($ret);
    }

    /**
     * Mark Read Message(s).
     *
     * @invokable
     *
     * @param int|array $conversation            
     *
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
     *
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
     * Get Service Service Conversation User.
     *
     * @return \Application\Service\ConversationUser
     */
    private function getServiceConversationUser()
    {
        return $this->container->get('app_service_conversation_user');
    }

    /**
     * Get Service Service Submission Conversation.
     *
     * @return \Application\Service\SubConversation
     */
    private function getServiceSubConversation()
    {
        return $this->container->get('app_service_sub_conversation');
    }

    /**
     * Get Service Service User.
     *
     * @return \Application\Service\User
     */
    private function getServiceUser()
    {
        return $this->container->get('app_service_user');
    }

    /**
     * Get Service Service Item.
     *
     * @return \Application\Service\Item
     */
    private function getServiceItem()
    {
        return $this->container->get('app_service_item');
    }

    /**
     * Get Service Service Message User.
     *
     * @return \Application\Service\MessageUser
     */
    private function getServiceMessageUser()
    {
        return $this->container->get('app_service_message_user');
    }

    /**
     * Get Service Service Submission.
     *
     * @return \Application\Service\Submission
     */
    private function getServiceSubmission()
    {
        return $this->container->get('app_service_submission');
    }

    /**
     * Get Service Service SubmissionUser.
     *
     * @return \Application\Service\SubmissionUser
     */
    private function getServiceSubmissionUser()
    {
        return $this->container->get('app_service_submission_user');
    }

    /**
     * Get Service Service Library.
     *
     * @return \Application\Service\Library
     */
    private function getServiceLibrary()
    {
        return $this->container->get('app_service_library');
    }

    /**
     * Get Service Service Conversation option.
     *
     * @return \Application\Service\ConversationOpt
     */
    private function getServiceConversationOpt()
    {
        return $this->container->get('app_service_conversation_opt');
    }

    /**
     * Get Service Service Conversation Document.
     *
     * @return \Application\Service\ConversationDoc
     */
    private function getServiceConversationDoc()
    {
        return $this->container->get('app_service_conversation_doc');
    }

    /**
     * Get Service Service Conversation TextEditor.
     *
     * @return \Application\Service\ConversationTextEditor
     */
    private function getServiceConversationTextEditor()
    {
        return $this->container->get('app_service_conversation_text_editor');
    }

    /**
     * Get Service Service Message.
     *
     * @return \Application\Service\Message
     */
    private function getServiceMessage()
    {
        return $this->container->get('app_service_message');
    }

    /**
     * Get Service Service SubTextEditor.
     *
     * @return \Application\Service\SubTextEditor
     */
    private function getServiceSubTextEditor()
    {
        return $this->container->get('app_service_sub_text_editor');
    }

    /**
     * Get Service Service TextEditor.
     *
     * @return \Application\Service\TextEditor
     */
    private function getServiceTextEditor()
    {
        return $this->container->get('app_service_text_editor');
    }

    /**
     * Get Service Service Conversation Whiteboard.
     *
     * @return \Application\Service\ConversationWhiteboard
     */
    private function getServiceConversationWhiteboard()
    {
        return $this->container->get('app_service_conversation_whiteboard');
    }

    /**
     * Get Service Service SubWhiteboard.
     *
     * @return \Application\Service\SubWhiteboard
     */
    private function getServiceSubWhiteboard()
    {
        return $this->container->get('app_service_sub_whiteboard');
    }

    /**
     * Get Service Service Document.
     *
     * @return \Application\Service\Document
     */
    private function getServiceDocument()
    {
        return $this->container->get('app_service_document');
    }

    /**
     * Get Service Service Whiteboard.
     *
     * @return \Application\Service\Whiteboard
     */
    private function getServiceWhiteboard()
    {
        return $this->container->get('app_service_whiteboard');
    }

    /**
     * Get Service Service OpenTok.
     *
     * @return \ZOpenTok\Service\OpenTok
     */
    private function getServiceZOpenTok()
    {
        return $this->container->get('opentok.service');
    }
}
