<?php

namespace Application\Service;

use Dal\Service\AbstractService;
use Application\Model\Item as ModelItem;
use Zend\Db\Sql\Predicate\IsNull;
use JRpc\Json\Server\Exception\JrpcException;
use Application\Model\SubmissionUser;
use Application\Model\Library as ModelLibrary;

class Submission extends AbstractService
{
    protected $sub = [
        ModelItem::TYPE_INDIVIDUAL_ASSIGNMENT => [
            ModelItem::CMP_TEXT_EDITOR => true,
            ModelItem::CMP_CHAT => true,
        ],
        ModelItem::TYPE_WORKGROUP => [
            ModelItem::CMP_VIDEOCONF => true,
            ModelItem::CMP_CHAT => true,
            ModelItem::CMP_TEXT_EDITOR => true,
        ],
        ModelItem::TYPE_LIVE_CLASS => [
            ModelItem::CMP_VIDEOCONF => true,
            ModelItem::CMP_CHAT => true,
        ],
        ModelItem::TYPE_CHAT => [
            ModelItem::CMP_CHAT => true,
        ],
        ModelItem::TYPE_CAPSTONE_PROJECT => [
            ModelItem::CMP_TEXT_EDITOR => true,
            ModelItem::CMP_CHAT => false,
        ],
        ModelItem::TYPE_DISCUSSION => [
            ModelItem::CMP_DISCUSSION => true,
        ],
        ModelItem::TYPE_DOCUMENT => [
            ModelItem::CMP_DOCUMENT => true,
        ],
        ModelItem::TYPE_EQCQ => [
            ModelItem::CMP_EQCQ => true,
        ],
        ModelItem::TYPE_MODULE => [
        ],
        ModelItem::TYPE_POLL => [
            ModelItem::CMP_POLL => true,
        ],
        ModelItem::TYPE_TXT => [
        ],
        
    ];
    
    /**
     * @param integer $user_id
     * @param integer $questionnaire_id
     * 
     * @return \Application\Service\Submission
     */
    public function getByUserAndQuestionnaire($user_id, $questionnaire_id, $item_id)
    {
        $res_submission = $this->getMapper()->getByUserAndQuestionnaire($user_id, $questionnaire_id);
        
        $m_submission = ($res_submission->count() <= 0) ?
             $this->get($item_id) :
             $res_submission->current();
        
        return $m_submission;
    }
    
    /**
     * 
     * @param integer $item_id
     * @param integer $user_id
     * @param integer $group_id
     * 
     * @return integer
     */
    public function create($item_id, $user_id = null, $group_id = null)
    {
        $m_item = $this->getServiceItem()->get($item_id);
        
        if(null === $user_id) {
            $user_id = $this->getServiceUser()->getIdentity()['id'];
        }
        
        if(is_numeric($m_item->getSetId()) && $group_id===null) {
            $group_id = $this->getServiceGroupUser()->getGroupIdByItemUser($item_id, $user_id);        
        }
        
        $submission_id = null;
        if($m_item->getType() === ModelItem::TYPE_LIVE_CLASS) {
            $m_submission = $this->get($item_id);
            if(null !== $m_submission) {
                $submission_id = $m_submission->getId();
            }
        }
        if(null === $submission_id) {
            $m_submission = $this->getModel()->setItemId($item_id)->setGroupId($group_id);
            $this->getMapper()->insert($m_submission);
            $submission_id = $this->getMapper()->getLastInsertValue();
        }
        
        $res_user = null;
        if(null !== $group_id) {
            $res_user = $this->getServiceUser()->getListUsersByGroup($group_id);
        }elseif($m_item->getType() === ModelItem::TYPE_LIVE_CLASS) {
            $res_user = $this->getServiceUser()->getListByItem($item_id);
        }
        
        $users = [];
        if(null === $res_user || $res_user->count() <= 0) {
            $users[] = $user_id;
        } else {
            foreach ($res_user as $m_user) {
                $users[] = $m_user->getId();
            }
        }
        
        $this->getServiceSubmissionUser()->create($submission_id, $users);
        
        return $submission_id;
    }
    
    /**
     * @invokable
     * 
     * @param integer $item_id
     * @param integer $submission_id
     * @param integer $group_id
     * @param integer $user_id
     * 
     * @return \Application\Model\Submission
     */
    public function get($item_id = null, $submission_id = null, $group_id = null, $user_id = null)
    {
        if(null === $item_id && null === $submission_id) {
            throw new \Exception('error item and submission are null in submission.get');
        }
        if(null === $user_id && null === $submission_id && null === $group_id) {
            $user_id = $this->getServiceUser()->getIdentity()['id'];
        }
        
        $res_submission = $this->getMapper()->get($item_id, $user_id, $submission_id, $group_id);
        
        if($res_submission->count() <= 0) {
            $submission_id = $this->create($item_id, $user_id, $group_id);
            $res_submission = $this->getMapper()->get(null, null, $submission_id);
        }
        
        $m_submission = $res_submission->current();
        $m_submission->setSubmissionUser($this->getServiceSubmissionUser()->getListBySubmissionId($m_submission->getId()));
        
        return $m_submission;
    }
    
    /**
     * @invokable
     *
     * @param integer $item_id
     */
    public function getList($item_id)
    {
        $me = $this->getServiceUser()->getIdentity()['id']; 
        $res_submission = $this->getMapper()->getList($item_id, $me);
        $m_item = $this->getServiceItem()->get($item_id);
     
        $by_set = (null !== $m_item->getSetId() && !$m_item->getSetId() instanceof IsNull);
        foreach ($res_submission as $m_submission) {
            if($by_set===true) {
                if(is_numeric($m_submission->getId())) {
                    $m_submission->setSubmissionUser($this->getServiceSubmissionUser()->getListBySubmissionId($m_submission->getId()));
                } else {
                    $res_user = $this->getServiceUser()->getListUsersByGroup($m_submission->getGroupId());
                    $su=[];
                    foreach ($res_user as $m_user) {
                        $m_submission_user = new SubmissionUser();
                        $m_submission_user->setUser($m_user);
                        $su[] = $m_submission_user;
                    }
                    $m_submission->setSubmissionUser($su);
                }
            } else {
                $m_submission->setSubmissionUser([$m_submission->getSubmissionUser()]);
            }
        }
        
        return $res_submission;
    }
    
    /**
     * @invokable
     * 
     * @param integer $grade
     * @param integer $item
     * @param integer $group
     * @param integer $submission
     * @param integer $user
     */
    public function updateSubmissionGrade($grade, $item = null, $group = null, $submission = null, $user = null) 
    {
        $m_submission = $this->get($item, $submission, $group, $user);

        $this->getServiceSubmissionUser()->OverwrittenGrade($m_submission->getId(), $grade);
           
        $this->getMapper()->update($this->getModel()->setIsGraded(true)->setId($m_submission->getId()));
        
        return $m_submission->getId();
    }
    
    /**
     * @param integer $id
     * 
     * @return \Application\Model\Submission
     */
    public function getBySubmission($id)
    {
        return $this->get(null, $id);
    }
    
    public function getListRecord($item, $user, $is_student)
    {
        $res_submission = $this->getMapper()->getListRecord($item, $user, $is_student);
        foreach ($res_submission as $m_submission) {
            $m_submission->setVideoconfArchives($this->getServiceVideoconfArchive()->getListRecordBySubmission($m_submission->getId()));
            $m_submission->setUsers($this->getServiceUser()->getListUsersBySubmission($m_submission->getId()));
        }
    
        return $res_submission;
    }
    
    /**
     * @invokable
     * 
     * @param integer $item_id
     * @param integer $user_id
     * @param integer $group_id
     * 
     * @return \Application\Model\Submission
     */
    public function getByItem($item_id, $user_id = null, $group_id = null)
    {
        return $this->get($item_id, null, $group_id, $user_id);
    }
    
    /**
     * @invokable
     * 
     * @param integer $submission_id
     */
    public function getContent($submission_id)
    {
        if(null === ($m_submission = $this->getBySubmission($submission_id))) {
            throw new JrpcException("Error no submission", 999);
        }
        
        $ret = [];
        $item_id = $m_submission->getItemId();
        $m_item = $this->getServiceItem()->get($item_id);
        $type = (isset($this->sub[$m_item->getType()])) ? $this->sub[$m_item->getType()] : [];
           
        if(isset($type[ModelItem::CMP_TEXT_EDITOR]) && $type[ModelItem::CMP_TEXT_EDITOR] === true) {
            $ret[ModelItem::CMP_TEXT_EDITOR] = $this->getServiceTextEditor()->getListOrCreate($submission_id);
        } else {
            $ret[ModelItem::CMP_TEXT_EDITOR] = $this->getServiceTextEditor()->getListBySubmission($submission_id);
        }
        
        $ret[ModelItem::CMP_DOCUMENT] = $this->getServiceLibrary()->getListBySubmission($submission_id);
        
        // Les composants seulement par groupe
        $ret[ModelItem::CMP_CHAT] = [];
        if($m_item->getSetId() !== null && ! $m_item->getSetId() instanceof IsNull) {
            if(isset($type[ModelItem::CMP_CHAT]) && $type[ModelItem::CMP_CHAT] === true) {
                $ret[ModelItem::CMP_CHAT] = $this->getServiceConversation()->getListOrCreate($submission_id);
            } else {
                $ret[ModelItem::CMP_CHAT] = $this->getServiceConversation()->getListBySubmission($submission_id);
            }
            
            if(isset($type[ModelItem::CMP_VIDEOCONF]) && $type[ModelItem::CMP_VIDEOCONF] === true) {
                $ret[ModelItem::CMP_VIDEOCONF] = $this->getServiceVideoconf()->joinUser(null,$submission_id);
            } else {
                $ret[ModelItem::CMP_VIDEOCONF] = $this->getServiceVideoconf()->getBySubmission($submission_id);
            }
        }
        
        if(isset($type[ModelItem::CMP_POLL]) && $type[ModelItem::CMP_POLL] === true) {
            $ret[ModelItem::CMP_POLL] = $this->getServiceSubQuiz()->getBySubmission($submission_id);
        }
        
        return $ret;
    }
    
    /**
     * @invokable
     * 
     * @param integer $submission_id
     * @param integer $item_id
     * @return void|boolean
     */
    public function submit($submission_id = null, $item_id = null) 
    {
        if($submission_id === null && $item_id === null) {
            return;
        }
        
        return ($submission_id !== null) ?
            $this->submitBySubmission($submission_id) :
            $this->submitByItem($item_id);
    }
    
    /**
     * @invokable
     * 
     * @param integer $submission_id
     * @return boolean
     */
    public function submitBySubmission($submission_id) 
    {
        $ret = true;
        $me = $this->getServiceUser()->getIdentity()['id'];
        $submit = 1;
        
        $res_submission_user = $this->getServiceSubmissionUser()->getListBySubmissionId($submission_id);
        foreach ($res_submission_user as $m_submission_user) {
            if($m_submission_user->getUserId() === $me) {
                $ret = $this->getServiceSubmissionUser()->submit($submission_id, $me);
            } else {
                $submit&=($m_submission_user->getSubmitDate()!==null && (!$m_submission_user->getSubmitDate() instanceof IsNull));
            }
        }
        if($submit===1) {
            $m_submission = $this->getModel()
                ->setSubmitDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'))
                ->setId($submission_id);
            $this->getMapper()->update($m_submission);
        }
        
        return $ret;
    }
    
    /**
     * @invokable
     * 
     * @param integer $item_id
     * @return boolean
     */
    public function submitByItem($item_id)
    {
        return $this->submitBySubmission($this->getByItem($item_id)->getId());
    }
    
    /**
     * @invokable
     *
     * @param integer $submission_id
     * @param integer $item_id
     * @return void|boolean
     */
    public function cancelsubmit($submission_id = null, $item_id = null)
    {
        if($submission_id === null && $item_id === null) {
            return;
        }
    
        return ($submission_id !== null) ?
        $this->cancelsubmitBySubmission($submission_id) :
        $this->cancelsubmitByItem($item_id);
    }
    
    /**
     * @invokable
     *
     * @param integer $submission_id
     * @return boolean
     */
    public function cancelsubmitBySubmission($submission_id)
    {
        
        $m_submission = $this->get(null,$submission_id);
        if(!($m_submission->getSubmitDate()=== null || $m_submission->getSubmitDate() instanceof IsNull)) {
            return;
        }
        
        return $this->getServiceSubmissionUser()->cancelsubmit($submission_id, $this->getServiceUser()->getIdentity()['id']);
    }
    
    /**
     * @invokable
     *
     * @param integer $item_id
     * @return boolean
     */
    public function cancelsubmitByItem($item_id)
    {
        return $this->cancelsubmitBySubmission($this->getByItem($item_id)->getId());
    }
    
    /**
     * @invokable
     * 
     * @param integer $submission_id
     * @param string $name
     * @param string $type
     * @param string $link
     * @param string $token
     */
    public function addDocument($submission_id, $name = null, $type = null, $link = null, $token = null)
    {
        return  $this->getServiceDocument()->add($name, $type, $link, $token,null, $submission_id, ModelLibrary::FOLDER_OTHER_INT);
    }
    
    /**
     * @invokable
     *
     * @param integer $submission_id
     * @param integer $library_id
     */
    public function deleteDocument($submission_id, $library_id)
    {
        return  $this->getServiceDocument()->delete(null, $submission_id, $library_id);
    }
      
    /**
     *
     * @return \Application\Service\GroupUser
     */
    public function getServiceGroupUser()
    {
        return $this->getServiceLocator()->get('app_service_group_user');
    }
    
    /**
     * 
     * @return \Application\Service\Library
     */
    public function getServiceLibrary()
    {
        return $this->getServiceLocator()->get('app_service_library');
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
    
    /**
     *
     * @return \Application\Service\Thread
     */
    public function getServiceThread()
    {
        return $this->getServiceLocator()->get('app_service_thread');
    }
    
    /**
     *
     * @return \Application\Service\User
     */
    public function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }
    
    /**
     *
     * @return \Application\Service\Videoconf
     */
    public function getServiceVideoconf()
    {
        return $this->getServiceLocator()->get('app_service_videoconf');
    }
    
    /**
     *
     * @return \Application\Service\SubmissionUser
     */
    public function getServiceSubmissionUser()
    {
        return $this->getServiceLocator()->get('app_service_submission_user');
    }
    
    /**
     *
     * @return \Application\Service\SubQuiz
     */
    public function getServiceSubQuiz()
    {
        return $this->getServiceLocator()->get('app_service_sub_quiz');
    }
    
    /**
     *
     * @return \Application\Service\VideoconfArchive
     */
    public function getServiceVideoconfArchive()
    {
        return $this->getServiceLocator()->get('app_service_videoconf_archive');
    }
    
}