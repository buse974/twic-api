<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Like
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class Like.
 */
class Like extends AbstractService
{
    /**
     * Add Liek to Event.
     *
     * @invokable
     *
     * @param int $event
     *
     * @return int
     */
    public function add($event)
    {
        $res = null;
        $me = $this->getServiceUser()->getIdentity()['id'];

        $m_like = $this->getModel()
            ->setEventId($event)
            ->setUserId($me);

        if ($this->getMapper()
            ->select($m_like)
            ->count() > 0) {
            $m_like->setIsLike(true);
            $res = $this->getMapper()->update($m_like, ['event_id' => $event, 'user_id' => $me]);
        } else {
            $m_like->setIsLike(true)->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));

            if ($this->getMapper()->insert($m_like) <= 0) {
                throw new \Exception('error add like');
            }

            $this->getServiceEvent()->userLike($event);

            $res = $this->getMapper()->getLastInsertValue();
        }

        return $res;
    }

    /**
     * UnLike Event.
     *
     * @invokable
     *
     * @param int $event
     *
     * @return int
     */
    public function delete($event)
    {
        $me = $this->getServiceUser()->getIdentity()['id'];

        return $this->getMapper()->update($this->getModel()
            ->setIsLike(false), ['event_id' => $event, 'user_id' => $me]);
    }

    /**
     * Get List Like to event.
     *
     * @invokable
     *
     * @param int $feed
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getList($feed)
    {
        return $this->getServiceUser()->getList(null, null, null, null, null, null, null, null, null, null, null, $feed);
    }

    /**
     * Get Service User.
     *
     * @return \Application\Service\User
     */
    private function getServiceUser()
    {
        return $this->container->get('app_service_user');
    }

    /**
     * Get Service Feed.
     *
     * @return \Application\Service\Feed
     */
    private function getServiceFeed()
    {
        return $this->container->get('app_service_feed');
    }

    /**
     * Get Service Contact.
     *
     * @return \Application\Service\Contact
     */
    private function getServiceContact()
    {
        return $this->container->get('app_service_contact');
    }

    /**
     * Get Service Event.
     *
     * @return \Application\Service\Event
     */
    private function getServiceEvent()
    {
        return $this->container->get('app_service_event');
    }
}
