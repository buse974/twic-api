<?php

namespace Application\Service;

use Dal\Service\AbstractService;
use DateTimeZone;
use DateTime;
use Application\Model\Videoconf as CVF;

class Videoconf extends AbstractService
{
    /**
     * @invokable
     *
     * @param string $title
     * @param string $description
     * @param string $start_date
     */
    public function add($title, $description, $start_date)
    {
        $m_videoconf = $this->getModel();
        $m_videoconf->setTitle($title)
                    ->setDescription($description)
                    ->setStartDate((new \DateTime($start_date))->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d H:i:s'))
                    ->setToken($this->getServiceZOpenTok()->getSessionId())
                    ->setCreatedDate((new DateTime('now', new DateTimeZone('UTC')))->format('Y-m-d H:i:s'));

        if ($this->getMapper()->insert($m_videoconf) === 0) {
            throw new \Exception('Error insert');
        }

        return $this->getMapper()->getLastInsertValue();
    }

    /**
     * Delete videoconf.
     *
     * @invokable
     *
     * @param int $id
     */
    public function delete($id)
    {
        $m_videoconf = $this->getModel()
                            ->setId($id)
                            ->setDeletedDate((new DateTime('now', new DateTimeZone('UTC')))->format('Y-m-d H:i:s'));

        return $this->getMapper()->update($m_videoconf);
    }

    /**
     * Get token videoconf by id.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return string
     */
    public function getToken($id)
    {
        $res_videoconf = $this->getMapper()->getToken($id);

        if ($res_videoconf->count() === 0) {
            throw new \Exception('Error select');
        }

        return $res_videoconf->current()->getToken();
    }

    /**
     * Get videoconf.
     *
     * @invokable
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return \Application\Model\Videoconf
     */
    public function get($id)
    {
        $res_videoconf = $this->getMapper()->get($id);

        if ($res_videoconf->count() === 0) {
            throw new \Exception('Error select');
        }

        $m_videoconf = $res_videoconf->current();
        $m_videoconf->setVideoconfInvitation($this->getServiceVideoConfInvitation()->getByVideoconfId($m_videoconf->getId())->toArray());

        return $m_videoconf;
    }

    /**
     * Get videoconf.
     *
     * @invokable
     *
     * @param string $token
     *
     * @throws \Exception
     *
     * @return \Application\Model\Videoconf
     */
    public function getRoom($token)
    {
        $res_videoconf = $this->getMapper()->getRoom($token);

        if ($res_videoconf->count() === 0) {
            throw new \Exception('Error select');
        }

        return $res_videoconf->current();
    }

    /**
     * Update Video Conf.
     *
     * @invokable
     *
     * @param int    $id
     * @param string $title
     * @param string $description
     * @param string $start_date
     *
     * @return int
     */
    public function update($id, $title = null, $description = null, $start_date = null)
    {
        $m_videoconf_tmp = $this->get($id);
        $m_videoconf = $this->getModel();
        $m_videoconf->setId($id)
                    ->setTitle($title)
                    ->setDescription($description)
                    ->setStartDate((new \DateTime($start_date))->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d H:i:s'));

        if ($start_date !== null && $m_videoconf_tmp->getStartDate() !== $start_date) {
            $res_videoconf_invitation = $this->getServiceVideoConfInvitation()->getByVideoconfId($id);
            if ($res_videoconf_invitation->count() > 0) {
                foreach ($res_videoconf_invitation as $m_videoconf_invitation) {
                    $this->getServiceMail()->sendTpl('tpl1', $m_videoconf_invitation->getEmail(), array(
                            'firstname' => $m_videoconf_invitation->getFirstname(),
                            'lastname' => $m_videoconf_invitation->getLastname(),
                            'link' => $this->getServiceLocator()->get('config')['path_videoconf_guest'].$m_videoconf_tmp->getToken(),
                            'start_date' => (new DateTime($start_date, new DateTimeZone('UTC')))->setTimezone(new DateTimeZone($m_videoconf_invitation->getUtc()))->format('Y-m-d H:i:s'),
                        )
                    );
                }
            }
        }

        return $this->getMapper()->update($m_videoconf);
    }

    /**
     * Get List video conf.
     *
     * @invokable
     *
     * @param array $filter
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getList(array $filter = array())
    {
        $m_videoconf = $this->getModel();

        $mapper = $this->getMapper();

        $res_videoconf = $mapper->usePaginator($filter)->select($m_videoconf);

        return array('count' => $mapper->count(), 'results' => $res_videoconf);
    }

    /**
     * Admin join video conf.
     *
     * @invokable
     *
     * @param string $token
     */
    public function implode($id)
    {
        return $this->get($id)->setVideoconfAdmin($this->getServiceVideoconfAdmin()->add($id));
    }

    /**
     * Start record video conf.
     *
     * @invokable
     *
     * @param string $token
     */
    public function record($token)
    {
        $res_videoconf = $this->getMapper()->getVideoconfTokenByTokenAdmin($token);

        if ($res_videoconf->count() === 0) {
            throw new \Exception('Error no videoconf');
        }

        $videoconf = $res_videoconf->current();

        $arr_archive = $this->getServiceZOpenTok()->startArchive($videoconf->getToken());

        if ($arr_archive['status'] == 'started') {
            $m_videoconf = $this->getModel();
            $m_videoconf->setId($videoconf->getId())
                        ->setArchiveToken($arr_archive['id'])
                        ->setArchiveStatus(CVF::ARV_STARTED);

            $this->getMapper()->update($m_videoconf);
        }

        return true;
    }

    /**
     * Récupére la liste des videos a uploader.
     *
     * @invokable
     *
     * @return array
     */
    public function getListVideoUpload()
    {
        $ret[] = array();

        $res_video_no_upload = $this->getMapper()->getListVideoUpload();

        foreach ($res_video_no_upload as $m_video_conf) {
            try {
                $archive = $this->getServiceZOpenTok()->getArchive($m_video_conf->getArchiveToken());

                if ($archive['status'] == CVF::ARV_AVAILABLE) {
                    $m_videoconf = $this->getModel();
                    $m_videoconf->setId($m_video_conf->getId())
                                ->setArchiveStatus(CVF::ARV_UPLOAD)
                                ->setDuration($archive['duration']);
                    $this->getMapper()->update($m_videoconf);

                    $arr = $m_video_conf->toArray();
                    $arr['url'] = $archive['url'];
                    $ret[] = $arr;
                }
            } catch (\Exception $e) {
                echo $e->getMessage();
                exit();
            }
        }

        return $ret;
    }

    /**
     * Valide le transfer video.
     *
     * @invokable
     *
     * @param int    $m_videoconf_id
     * @param string $url
     *
     * @return int
     */
    public function validTransfertVideo($m_videoconf_id, $url)
    {
        $m_videoconf = $this->getModel();
        $m_videoconf->setId($m_videoconf_id)
                    ->setArchiveStatus(CVF::ARV_AVAILABLE)
                    ->setArchiveLink($url);

        return $this->getMapper()->update($m_videoconf);
    }

    /**
     * @return \Application\Service\VideoconfInvitation
     */
    public function getServiceVideoConfInvitation()
    {
        return $this->getServiceLocator()->get('app_service_videoconf_invitation');
    }

    /**
     * @return \Application\Service\VideoconfAdmin
     */
    public function getServiceVideoconfAdmin()
    {
        return $this->getServiceLocator()->get('app_service_videoconf_admin');
    }

    /**
     * @return \ZOpenTok\Service\OpenTok
     */
    public function getServiceZOpenTok()
    {
        return $this->getServiceLocator()->get('opentok.service');
    }

    /**
     * @return \Mail\Service\Mail
     */
    public function getServiceMail()
    {
        return $this->getServiceLocator()->get('mail.service');
    }
}
