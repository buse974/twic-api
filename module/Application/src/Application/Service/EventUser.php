<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class EventUser extends AbstractService
{
    public function add($user, $notification)
    {
        if (!is_array($user)) {
            $user = [$user];
        }
        $m_event_user = $this->getModel()->setEventId($notification);

        foreach ($user as $u) {
            $m_event_user->setUserId($u);
            $this->getMapper()->insert($m_event_user);
        }

        return true;
    }

    /**
     * @invokable
     */
    public function read($ids = null, $event = null)
    {
        $nb = 0;
        $me = $this->getServiceUser()->getIdentity()['id'];

        $date = (new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s');

        if (null !== $ids) {
            $m_event = $this->getModel()->setUserId($me)->setReadDate($date)->setEventId($ids);
            $nb = $this->getMapper()->update($m_event);
        } else {
            $nb += $this->getMapper()->insertUpdate($date, $me, $event);
            $nb += $this->getMapper()->updateReadMe($date, $me, $event);
        }

        return $nb;
    }

    /**
     * @return \Application\Service\User
     */
    public function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }
}
