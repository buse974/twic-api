<?php
namespace Application\Service;

use Dal\Service\AbstractService;

class Poll extends AbstractService
{

    /**
     * Add poll for message.
     *
     * {
     * tilte,
     * expiration,
     *      poll_questions : [{
     *          mandatory,
     *          question,
     *          question_type,
     *          question_items : {
     *              libelle,
     *              parent,
     *          },..]
     *      },...]
     * }
     *
     * @invokable
     * 
     * @param integer $message            
     * @param array $datas            
     */
    public function add($title, $poll_questions, $expiration = null)
    {
        $m_poll = $this->getModel();
        $m_poll->setExpirationDate($expiration)
            ->setTitle($title);
        
        if ($this->getMapper()->insert($m_poll) < 1) {
            throw new \Exception('Insert poll error');
        }
        
        $poll_id = $this->getMapper()->getLastInsertValue();
        
        foreach ($poll_questions as $poll_question) {
            $this->getServicePollQuestion()->add($poll_id, 
                (isset($poll_question['question']) ? $poll_question['question']:null),
                (isset($poll_question['poll_question_type']) ? $poll_question['poll_question_type']:1),
                (isset($poll_question['poll_question_items']) ? $poll_question['poll_question_items']:[]),
                (isset($poll_question['mandatory']) ? $poll_question['mandatory']:false),
                (isset($poll_question['parent']) ? $poll_question['parent']:null));
        }
        
        return $this->get($poll_id);
    }

    /**
     * @invokable
     * 
     * @param integer $id
     * 
     * @throws \Exception
     */
    public function get($id)
    {
        $m_poll = $this->getModel();
        $m_poll->setId($id);
        
        $res_poll = $this->getMapper()->select($m_poll);
        
        if ($res_poll->count() !== 1) {
            throw new \Exception('poll not exist');
        }
        
        $m_poll = $res_poll->current();
        $m_poll->setPollQuestions($this->getServicePollQuestion()->getList($id));
        
        return $m_poll;
    }

    /**
     * @invokable
     * 
     * @param integer $poll
     * @param integer $poll_question
     * @param array $items
     */
    public function vote($poll, $poll_question, $items)
    {       
        return $this->getServicePollAnswer()->add($poll, $poll_question, $items);
    }
    
    /**
     * @invokable
     *
     * @param integer $id
     */
    public function delete($id)
    {
        return $this->getMapper()->delete($this->getModel()->setId($id));
    }
    
    /**
     *
     * @return \Application\Service\PollAnswer
     */
    public function getServicePollAnswer()
    {
        return $this->getServiceLocator()->get('app_service_poll_answer');
    }
    
    /**
     *
     * @return \Application\Service\PollQuestion
     */
    public function getServicePollQuestion()
    {
        return $this->getServiceLocator()->get('app_service_poll_question');
    }
}