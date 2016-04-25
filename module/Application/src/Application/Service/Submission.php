<?php

namespace Application\Service;

use Dal\Service\AbstractService;
use Application\Model\Item as ModelItem;
use Zend\Db\Sql\Predicate\IsNull;
use JRpc\Json\Server\Exception\JrpcException;

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
            ModelItem::CMP_TEXT_EDITOR => false,
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
    
    public function create($item_id)
    {
        $me = $this->getServiceUser()->getIdentity()['id'];
        $m_submission = $this->getModel()->setItemId($item_id);
        $this->getMapper()->insert($m_submission);
        $submission_id = $this->getMapper()->getLastInsertValue();
        
        $res_user = $this->getServiceUser()->getListUsersGroupByItemAndUser($item_id, $me);
        
        $users = [];
        if($res_user->count() <= 0) {
            $users[] = $me;
        } else {
            foreach ($res_user as $m_user) {
                $users[] = $m_user->getId();
            }
        }
        
        return $this->getServiceSubmissionUser()->create($submission_id, $users);
    }
    
    /**
     * @invokable
     * 
     * @param integer $item_id
     * @param integer $submission_id
     * 
     *  @return \Application\Model\Submission
     */

    public function get($item_id = null, $submission_id = null)
    {
        if($item_id !== null) {
            return $this->getByItem($item_id);
        } elseif ($submission_id !== null) {
            return $this->getBySubmission($submission_id);
        }
    }
    
    /**
     * 
     * @param integer $id
     * 
     * @return \Application\Model\Submission
     */
    public function getBySubmission($id)
    {
        $m_submission = null;
        $res_submission = $this->getMapper()->select($this->getModel()->setId($id));
        
        if ($res_submission->count() > 0) {
            $m_submission = $this->getByItem($res_submission->current()->getItemId()); 
        }
        
        return $m_submission;
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
     * 
     * @return \Application\Model\Submission
     */
    public function getByItem($item_id)
    {
        $me = $this->getServiceUser()->getIdentity()['id'];
    
        $res_submission = $this->getMapper()->get($item_id, $me);
    
        if($res_submission->count() <= 0) {
            $this->create($item_id);
            $res_submission = $this->getMapper()->get($item_id, $me);
        }
    
        $m_submission = $res_submission->current();
        $m_submission->setSubmissionUser($this->getServiceSubmissionUser()->getListBySubmissionId($m_submission->getId()));
    
        return $m_submission;
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
        if($m_item->getSetId() !== null) {
            if(isset($type[ModelItem::CMP_CHAT]) && $type[ModelItem::CMP_CHAT] === true) {
                $ret[ModelItem::CMP_CHAT] = $this->getServiceConversation()->getListOrCreate($submission_id);
            } else {
                $ret[ModelItem::CMP_CHAT] = $this->getServiceConversation()->getListBySubmission($submission_id);
            }
            
            if(isset($type[ModelItem::CMP_VIDEOCONF]) && $type[ModelItem::CMP_VIDEOCONF] === true) {
                $ret[ModelItem::CMP_VIDEOCONF] = $this->getServiceVideoconf()->getBySubmission($submission);
            } else {
                $ret[ModelItem::CMP_VIDEOCONF] = $this->getServiceVideoconf()->getBySubmission($submission);
            }
        }
        
        
        
        
        
        
        //ret[ModelItem::CMP_DISCUSSION] = $this->getServic()->getListBySubmission($submission_id);
        /*$ret[ModelItem::CMP_DISCUSSION] = $this->getServiceConversation()->get($item_id);
         $ret[ModelItem::CMP_DOCUMENT] = $this->getServiceConversation()->get($item_id);
         $ret[ModelItem::CMP_EQCQ] = $this->getServiceConversation()->get($item_id);
         $ret[ModelItem::CMP_POLL] = $this->getServiceConversation()->get($item_id);
         */
        
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
     * 
     * @return \Application\Service\Library
     */
    public function getServiceLibrary()
    {
        return $this->getServiceLocator()->get('app_service_library');
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
     * @return \Application\Service\VideoconfArchive
     */
    public function getServiceVideoconfArchive()
    {
        return $this->getServiceLocator()->get('app_service_videoconf_archive');
    }
    
}