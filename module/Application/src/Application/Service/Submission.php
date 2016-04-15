<?php

namespace Application\Service;

use Dal\Service\AbstractService;
use Application\Model\Item as ModelItem;

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
    
    public function getByUserAndQuestionnaire($me, $questionnaire)
    {
        $m_submission = $this->getMapper()->getByUserAndQuestionnaire($me, $questionnaire);
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
        return $this->getMapper()->select($this->getModel()->setId($id))->current();
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
        $ret = [];
        $item_id = $this->getBySubmission($submission_id)->getItemId();
        $m_item = $this->getServiceItem()->get($item_id);
        $type = (isset($this->sub[$m_item->getType()])) ? $this->sub[$m_item->getType()] : [];
           
        if(isset($type[ModelItem::CMP_TEXT_EDITOR]) && $type[ModelItem::CMP_TEXT_EDITOR] === true) {
            $ret[ModelItem::CMP_TEXT_EDITOR] = $this->getServiceTextEditor()->getListOrCreate($submission_id);
        } else {
            $ret[ModelItem::CMP_TEXT_EDITOR] = $this->getServiceTextEditor()->getListBySubmission($submission_id);
        }
        
        if(isset($type[ModelItem::CMP_CHAT]) && $type[ModelItem::CMP_CHAT] === true) {
            $ret[ModelItem::CMP_CHAT] = $this->getServiceConversation()->getListOrCreate($submission_id);
        } else {
            $ret[ModelItem::CMP_CHAT] = $this->getServiceConversation()->getListBySubmission($submission_id);
        }
        
        $ret[ModelItem::CMP_DOCUMENT] = $this->getServiceLibrary()->getListBySubmission($submission_id);
        
        /*$ret[ModelItem::CMP_DISCUSSION] = $this->getServiceConversation()->get($item_id);
         $ret[ModelItem::CMP_DOCUMENT] = $this->getServiceConversation()->get($item_id);
         $ret[ModelItem::CMP_EQCQ] = $this->getServiceConversation()->get($item_id);
         $ret[ModelItem::CMP_POLL] = $this->getServiceConversation()->get($item_id);
         $ret[ModelItem::CMP_VIDEOCONF] = $this->getServiceConversation()->get($item_id);*/
        
        return $ret;
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
     * @return \Application\Service\User
     */
    public function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }
    
    /**
     *
     * @return \Application\Service\SubmissionUser
     */
    public function getServiceSubmissionUser()
    {
        return $this->getServiceLocator()->get('app_service_submission_user');
    }
}