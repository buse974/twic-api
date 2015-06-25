<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class VideoconfInvitation extends AbstractService
{
    /**
     * Add participant to videoconf.
     *
     * @invokable
     *
     * @param int    $videoconf_id
     * @param string $firstname
     * @param string $lastname
     * @param string $email
     * @param string $entity
     * @param string $avatar
     *
     * @throws \Exception
     *
     * @return int
     */
    public function add($videoconf_id, $firstname, $lastname, $email, $entity, $utc, $avatar = null)
    {
        $videoconf_entity = $this->getServiceVideoconfEntity()->add($entity, $videoconf_id, $avatar);
        $videoconf = $this->getServiceVideoconf()->get($videoconf_id);

        $m_videoconf_invitation = $this->getModel();
        $m_videoconf_invitation->setVideoconfEntityId($videoconf_entity->getId())
                               ->setFirstname($firstname)
                               ->setLastname($lastname)
                               ->setAvatar($avatar)
                               ->setUtc($utc)
                               ->setEmail($email);

        if ($this->getMapper()->insert($m_videoconf_invitation) === 0) {
            throw new \Exception('Error insert');
        }

        $this->getServiceMail()->sendTpl('tpl1', $email, array(
                'firstname' => $firstname,
                'lastname' => $lastname,
                'link' => $this->getServiceLocator()->get('config')['path_videoconf_guest'].$videoconf_entity->getToken(),
                'start_date' => (new \DateTime($videoconf->getStartDate(), new \DateTimeZone('UTC')))->setTimezone(new \DateTimeZone($utc))->format('Y-m-d H:i:s'),

        ));

        return $this->getMapper()->getLastInsertValue();
    }

    /**
     * Delete participant.
     *
     * @invokable
     *
     * @param int $id
     *
     * @return int
     */
    public function delete($id)
    {
        return $this->getMapper()->delete($this->getModel()->setId($id));
    }

    /**
     * @param int $videoconf_id
     */
    public function getByVideoconfId($videoconf_id)
    {
        return $this->getMapper()->getByVideoconfId($videoconf_id);
    }

    /**
     * @return \Application\Service\VideoconfEntity
     */
    public function getServiceVideoconfEntity()
    {
        return $this->getServiceLocator()->get('app_service_videoconf_entity');
    }

    /**
     * @return \Application\Service\Videoconf
     */
    public function getServiceVideoconf()
    {
        return $this->getServiceLocator()->get('app_service_videoconf');
    }

    /**
     * @return \Mail\Service\Mail
     */
    public function getServiceMail()
    {
        return $this->getServiceLocator()->get('mail.service');
    }
}
