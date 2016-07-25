<?php
/**
 * 
 * TheStudnet (http://thestudnet.com)
 *
 * Archive Video
 * 
 */
namespace Application\Service;

use Dal\Service\AbstractService;
use Application\Model\VideoArchive as CVF;

/**
 * Class VideoArchive
 */
class VideoArchive extends AbstractService
{

    /**
     * Get List Video
     * @invokable
     *
     * @param integer $submission_id            
     * @return array|stdClass
     */
    public function getList($submission_id)
    {
        $res_videoconf_archive = $this->getMapper()->getList($submission_id);
        
        $ret = [];
        foreach ($res_videoconf_archive as $m_videoconf_archive) {
            $ret[$m_videoconf_archive->getSubmissionId()][] = $m_videoconf_archive->toArray();
        }
        
        return (count($ret) === 0) ? new \stdClass() : $ret;
    }

    /**
     * Get Video
     *
     * @param integer $id            
     * @return \Application\Model\VideoArchive
     */
    public function get($id)
    {
        $res_videoconf_archive = $this->getMapper()->get($id);
        if ($res_videoconf_archive->count() <= 0) {
            throw new \Exception("video_conf");
        }
        
        return $res_videoconf_archive->current();
    }

    /**
     * Start record video conf.
     *
     * @invokable
     *
     * @param interger $conversation_id            
     * @return array
     */
    public function startRecord($conversation_id)
    {
        $m_conversation = $this->getServiceConversation()->getLite($conversation_id);
        
        $arr_archive = json_decode($this->getServiceZOpenTok()->startArchive($m_conversation->getToken()), true);
        if ($arr_archive['status'] == 'started') {
            $this->add($m_conversation->getId(), $arr_archive['id']);
        }
        
        return $arr_archive;
    }

    /**
     * Stop record video conf.
     *
     * @invokable
     *
     * @param interger $conversation_id            
     * @return mixed
     */
    public function stopRecord($conversation_id)
    {
        $res_video_archive = $this->getMapper()->getLastArchiveId($conversation_id);
        if ($res_video_archive->count() <= 0) {
            throw new \Exception("no video with conversation: " . $conversation_id);
        }
        $m_video_archive = $res_video_archive->current();
        
        return $this->getServiceZOpenTok()->stopArchive($m_video_archive->getArchiveToken());
    }

    /**
     * Valide the video transfer
     *
     * @invokable
     *
     * @param interger $video_archive            
     * @param string $url            
     * @return int
     */
    public function validTransfertVideo($video_archive, $url)
    {
        $event_send = true;
        // regarder si une video na pas etait déja notifier pour cette conversation
        $res_video_archive = $this->getMapper()->getListSameConversation($video_archive);
        foreach ($res_video_archive as $m_video_archive) {
            if (CVF::ARV_AVAILABLE === $m_video_archive->getArchiveStatus()) {
                $event_send = false;
            }
        }
        
        //@todo check video first
        $ret = $this->updateByArchiveToken($video_archive, CVF::ARV_AVAILABLE, null, $url);
      //  if ($event_send) {
            $this->getServiceEvent()->recordAvailable($m_video_archive->getSubmissionId(), $video_archive);
      //  }
        
        return $ret;
    }

    /**
     * Update Status Video
     *
     * @param string $token            
     * @param string $status            
     * @param int $duration            
     * @param string $link            
     * @return int
     */
    public function updateByArchiveToken($id, $status, $duration = null, $link = null)
    {
        $m_video_archive = $this->getModel();
        $m_video_archive->setId($id)
            ->setArchiveDuration($duration)
            ->setArchiveStatus($status)
            ->setArchiveLink($link);
        
        return $this->getMapper()->update($m_video_archive);
    }

    /**
     * Get List videos a uploader.
     *
     * @invokable
     *
     * @return array
     */
    public function getListVideoUpload()
    {
        $ret = [];
        $res_video_no_upload = $this->getMapper()->getListVideoUpload();
        foreach ($res_video_no_upload as $m_video_archive) {
            try {
                $archive = json_decode($this->getServiceZOpenTok()->getArchive($m_video_archive->getArchiveToken()), true);
                if ($archive['status'] == CVF::ARV_AVAILABLE) {
                    $this->updateByArchiveToken($m_video_archive->getId(), CVF::ARV_UPLOAD, $archive['duration']);
                    $arr = $m_video_archive->toArray();
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
     * Add Video
     *
     * @param int $conversation            
     * @param string $token            
     *
     * @return int
     */
    public function add($conversation_id, $token)
    {
        $m_video_archive = $this->getModel();
        $m_video_archive->setConversationId($conversation_id)
            ->setArchiveToken($token)
            ->setArchiveStatus(CVF::ARV_STARTED)
            ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
        
        $this->getMapper()->insert($m_video_archive);
        
        return $this->getMapper()->getLastInsertValue();
    }

    /**
     * Get Service Conversation
     *
     * @return \Application\Service\Conversation
     */
    private function getServiceConversation()
    {
        return $this->getServiceLocator()->get('app_service_conversation');
    }

    /**
     * Get Service OpenTok
     *
     * @return \ZOpenTok\Service\OpenTok
     */
    private function getServiceZOpenTok()
    {
        return $this->getServiceLocator()->get('opentok.service');
    }

    /**
     * Get Service Event
     *
     * @return \Application\Service\Event
     */
    private function getServiceEvent()
    {
        return $this->getServiceLocator()->get('app_service_event');
    }
}