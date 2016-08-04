<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Event User
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class EventUser.
 */
class EventUser extends AbstractService
{
    /**
     * Add Event User.
     * 
     * @param int|array $user
     * @param int       $event_id
     *
     * @return bool
     */
    public function add($user_id, $event_id)
    {
        if (!is_array($user_id)) {
            $user_id = [$user_id];
        }
        $m_event_user = $this->getModel()->setEventId($event_id);
        foreach ($user_id as $u) {
            $m_event_user->setUserId($u);
            $this->getMapper()->insert($m_event_user);
        }

        return true;
    }

    /**
     * Mark Read Event User current.
     * 
     * @invokable
     * 
     * @param array  $ids
     * @param string $event
     *
     * @return int
     */
    public function read($ids = null, $event = null)
    {
        $nb = 0;
        $me = $this->getServiceUser()->getIdentity()['id'];

        $date = (new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s');

        if (null !== $ids) {
            $m_event = $this->getModel()
                ->setUserId($me)
                ->setReadDate($date)
                ->setEventId($ids);
            $nb = $this->getMapper()->update($m_event);
        } else {
            $nb += $this->getMapper()->insertUpdate($date, $me, $event);
            $nb += $this->getMapper()->updateReadMe($date, $me, $event);
        }

        return $nb;
    }

    /**
     * Mark View All Event User current.
     * 
     * @invokable
     * 
     * @param int $id
     *
     * @return array
     */
    public function view($id)
    {
        $nb = 0;
        $me = $this->getServiceUser()->getIdentity()['id'];

        $date = (new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s');

        $m_event_user = $this->getModel()->setUserId($me)->setEventId($id);
        $count = $this->getMapper()->select($m_event_user)->count();
        $m_event_user->setViewDate($date);

        return ($count > 0) ? $this->getMapper()->update($m_event_user) : $this->getMapper()->insert($m_event_user);
    }

    /**
     * Get Service User.
     * 
     * @return \Application\Service\User
     */
    private function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }
}
