<?php
namespace Application\Service;

use Dal\Service\AbstractService;

class Poll extends AbstractService
{

    /**
     * Add poll for message.
     * 
     * @invokable
     * 
     * @param string $title
     * @param integer $poll_item
     * @param integer $expiration
     * @param integer $time_limit
     * @param integer $item_id
     * @throws \Exception
     */
    public function add($title, $poll_item, $expiration = null, $time_limit = null, $item_id = null)
    {
        $m_poll = $this->getModel();
        $m_poll->setExpirationDate($expiration)
               ->setTitle($title)
               ->setTimeLimit($time_limit)
               ->setItemId($item_id);
        
        if ($this->getMapper()->insert($m_poll) < 1) {
            throw new \Exception('Insert poll error');
        }
        
        $poll_id = $this->getMapper()->getLastInsertValue();
        $this->getServicePollItem()->add($poll_id, $poll_item);
            
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
        //$m_poll->setPollQuestions($this->getServicePollQuestion()->getList($id));
        
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
     * @return \Application\Service\PollItem
     */
    public function getServicePollItem()
    {
        return $this->getServiceLocator()->get('app_service_poll_item');
    }
}