<?php

namespace Application\Service;

use Dal\Service\AbstractService;
use Application\Model\Videoconf as CVF;

class VideoconfArchive extends AbstractService
{
    /**
     * @param int    $videoconf
     * @param string $token
     *
     * @return int
     */
    public function add($videoconf, $token)
    {
        $m_videoconf_archive = $this->getModel();
        $m_videoconf_archive->setVideoconfId($videoconf)
            ->setArchiveToken($token)
            ->setArchiveStatus(CVF::ARV_STARTED)
            ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));

        $this->getMapper()->insert($m_videoconf_archive);

        return $this->getMapper()->getLastInsertValue();
    }

    /**
     * @param string $token
     * @param string $status
     * @param int    $duration
     * @param string $link
     *
     * @return int
     */
    public function updateByArchiveToken($id, $status, $duration = null, $link = null)
    {
        $m_videoconf_archive = $this->getModel();
        $m_videoconf_archive->setId($id)
            ->setArchiveDuration($duration)
            ->setArchiveStatus($status)
            ->setArchiveLink($link);

        return $this->getMapper()->update($m_videoconf_archive);
    }

    /**
     * @param integer $videoconf
     *
     * @return \Application\Model\VideoconfArchive
     */
    public function getLastArchiveId($videoconf)
    {
        return $this->getMapper()->getLastArchiveId($videoconf);
    }
    
    /**
     * @param int $id
     *
     * @return \Application\Model\VideoconfArchive
     */
    public function get($id)
    {
        return $this->getMapper()->select($this->getModel()->setId($id))->current();
    }

    public function getListVideoUpload()
    {
        return $this->getMapper()->getListVideoUpload();
    }

    public function getListRecordByItemProg($item_prog)
    {
        return $this->getMapper()->getListRecordByItemProg($item_prog);
    }
}
