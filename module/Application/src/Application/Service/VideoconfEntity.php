<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class VideoconfEntity extends AbstractService
{
    /**
     * @param string $name
     * @param int    $videoconf_id
     * @param string $avatar
     *
     * @return \Application\Model\VideoconfEntity
     */
    public function add($name, $videoconf_id, $avatar = null)
    {
        $m_videoconf_entity = $this->getModel();
        $m_videoconf_entity->setName($name)
                           ->setVideoconfId($videoconf_id);

        $res_videoconf_entity = $this->getMapper()->select($m_videoconf_entity);

        if ($res_videoconf_entity->count() === 0) {
            $token = $this->getServiceZOpenTok()->createToken($this->getServiceVideoconf()->getToken($videoconf_id), '{"entity": "'.htmlentities($name).'", "avatar": "'.$avatar.'"}');
            $m_videoconf_entity->setToken($token);
            $m_videoconf_entity->setAvatar($avatar);
            $this->getMapper()->insert($m_videoconf_entity);

            return $m_videoconf_entity->setId($this->getMapper()->getLastInsertValue());
        } else {
            return $res_videoconf_entity->current();
        }
    }

    /**
     * @return \Application\Service\Videoconf
     */
    public function getServiceVideoconf()
    {
        return $this->getServiceLocator()->get('app_service_videoconf');
    }

    /**
     * @return \ZOpenTok\Service\OpenTok
     */
    public function getServiceZOpenTok()
    {
        return $this->getServiceLocator()->get('opentok.service');
    }
}
