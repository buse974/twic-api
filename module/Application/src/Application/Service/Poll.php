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
            $this->getServicePollQuestion()->add($poll_id, $poll_question);
        }
        
        return $poll_id;
    }

    public function get($id)
    {
        $m_poll = $this->getModel();
        $m_poll->setId($id);
        
        $res_poll = $this->getMapper()->select($m_poll);
        
        if ($res_poll->count() !== 1) {
            throw new \Exception('poll not exist');
        }
        
        $m_poll = $res_poll->current();
        $m_poll->setQuestions($this->getServicePollQuestion()->getList($id));
        
        return $m_poll;
    }

    /**
     *
     * @return \Application\Service\PollQuestion
     */
    public function getServicePollQuestion()
    {
        return $this->getServiceLocator()->get('dal_service_poll_question');
    }
}