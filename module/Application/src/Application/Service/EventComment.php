<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Event Comment
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class Event Comment.
 */
class EventComment extends AbstractService
{
    /**
     * Add Event Comment.
     *
     * @invokable
     *
     * @param int    $comment
     * @param string $content
     *
     * @return int
     */
    public function add($event, $content)
    {
        $me = $this->getServiceUser()->getIdentity()['id'];

        $m_comment = $this->getModel()
            ->setEventId($event)
            ->setUserId($me)
            ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'))
            ->setContent($content);

        $this->getMapper()->insert($m_comment);

        $id = $this->getMapper()->getLastInsertValue();

        $this->getServiceEvent()->userComment($m_comment->setId($id));

        return $id;
    }

    /**
     * Update Event Comment.
     *
     * @invokable
     *
     * @param int    $comment
     * @param string $content
     *
     * @return int
     */
    public function update($comment, $content)
    {
        $me = $this->getServiceUser()->getIdentity()['id'];

        return $this->getMapper()->update($this->getModel()
            ->setId($comment)
            ->setContent($content), ['user_id' => $me, 'id' => $comment]);
    }

    /**
     * Delete Event Comment.
     *
     * @invokable
     *
     * @param int $comment
     *
     * @return int
     */
    public function delete($comment)
    {
        $me = $this->getServiceUser()->getIdentity()['id'];

        return $this->getMapper()->update($this->getModel()
            ->setId($comment)
            ->setDeletedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s')), ['user_id' => $me, 'id' => $comment]);
    }

    /**
     * Get List Event.
     *
     * @invokable
     *
     * @param int $event
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getList($event)
    {
        return $this->getMapper()->getList($event);
    }

    /**
     * Get Service Event.
     *
     * @return \Application\Service\Event
     */
    private function getServiceEvent()
    {
        return $this->serviceLocator->get('app_service_event');
    }

    /**
     * Get Service User.
     *
     * @return \Application\Service\User
     */
    private function getServiceUser()
    {
        return $this->serviceLocator->get('app_service_user');
    }
}
