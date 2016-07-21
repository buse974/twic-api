<?php
/**
 * 
 * TheStudnet (http://thestudnet.com)
 *
 * Poll
 *
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class Poll
 */
class Poll extends AbstractService
{

    /**
     * Add/Update Poll
     * 
     * @param int $item_id            
     * @param string $title            
     * @param int $poll_item            
     * @param string $expiration            
     * @param int $time_limit            
     * @return int
     */
    public function addOrUpdate($item_id, $title = null, $poll_item = null, $expiration = null, $time_limit = null)
    {
        return (null !== ($m_poll = $this->getByItem($item_id))) ? $this->update($m_poll->getId(), $title, $poll_item, $expiration, $time_limit) : $this->add($title, $poll_item, $expiration, $time_limit, $item_id);
    }

    /**
     * Add poll for message.
     *
     * @invokable
     *
     * @param string $title            
     * @param int $poll_item            
     * @param int $expiration            
     * @param int $time_limit            
     * @param int $item_id            
     *
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
     * update poll
     *
     * @invokable
     *
     * @param int $id            
     * @param string $title            
     * @param int $poll_item            
     * @param int $expiration            
     * @param int $time_limit            
     * @param int $item_id   
     * @return int         
     */
    public function update($id, $title = null, $poll_item = null, $expiration = null, $time_limit = null, $item_id = null)
    {
        $m_poll = $this->getModel();
        $m_poll->setId($id)
            ->setExpirationDate($expiration)
            ->setTitle($title)
            ->setTimeLimit($time_limit)
            ->setItemId($item_id);
        
        if (null !== $poll_item) {
            $this->getServicePollItem()->replace($id, $poll_item);
        }
        
        return $this->getMapper()->update($m_poll);
    }

    /**
     * Get Poll
     *
     * @invokable
     *
     * @param int $id            
     * @throws \Exception
     * @return \Application\Model\Poll
     */
    public function get($id)
    {
        $res_poll = $this->getMapper()->select($this->getModel()
            ->setId($id));
        
        if ($res_poll->count() !== 1) {
            throw new \Exception('poll not exist');
        }
        
        $m_poll = $res_poll->current();
        $m_poll->setPollItem($this->getServicePollItem()
            ->getList($m_poll->getId()));
        
        return $m_poll;
    }

    /**
     * Get Lite
     *
     * @param int $id            
     * @return null|\Application\Model\Poll
     */
    public function getLite($id)
    {
        $res_poll = $this->getMapper()->select($this->getModel()
            ->setId($id));
        if ($res_poll->count() !== 1) {
            throw new \Exception('poll not exist');
        }
        
        return $res_poll->current();
    }

    /**
     * Get By Item
     *
     * @param int $item_id            
     * @return \Application\Model\Poll
     */
    public function getByItem($item_id)
    {
        $res_poll = $this->getMapper()->select($this->getModel()
            ->setItemId($item_id));
        
        if ($res_poll->count() <= 0) {
            return;
        }
        
        $m_poll = $res_poll->current();
        $m_poll->setPollItem($this->getServicePollItem()
            ->getList($m_poll->getId()));
        
        return $m_poll;
    }

    /**
     * Get Lite By Item
     *
     * @param int $item_id            
     * @return \Application\Model\Poll
     */
    public function getLiteByItem($item_id)
    {
        return $this->getMapper()
            ->select($this->getModel()
            ->setItemId($item_id))
            ->current();
    }

    /**
     * Delete Poll
     *
     * @invokable
     *
     * @param int $id            
     * @return int
     */
    public function delete($id)
    {
        return $this->getMapper()->delete($this->getModel()
            ->setId($id));
    }

    /**
     * Get Servie PollItem
     *
     * @return \Application\Service\PollItem
     */
    private function getServicePollItem()
    {
        return $this->getServiceLocator()->get('app_service_poll_item');
    }
}
