<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class VideoArchive extends AbstractService
{
    
    /**
     * Start record video conf.
     *
     * @invokable
     *
     * @param interger $conversation_id
     */
    public function startRecord($conversation_id)
    {
        $m_conversation = $this->getServiceConversation()->getLite($id);

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
     */
    public function stopRecord($conversation_id)
    {
        $m_video_archive = $this->getLastArchiveId($conversation_id);
    
        return $this->getServiceZOpenTok()->stopArchive($m_video_archive->getArchiveToken());
    }
    
    /**
     * Valide le transfer video.
     *
     * @invokable
     *
     * @param interger $video_archive
     * @param string   $url
     *
     * @return int
     */
    public function validTransfertVideo($video_archive, $url)
    {
        $event_send = true;
        $m_videoconf = $this->getByVideoconfArchive($video_archive);
        
        
        $res_video_archive = $this->getListByVideoConf($m_videoconf->getId());
    
        foreach ($res_video_archive as $m_video_archive) {
            if (CVF::ARV_AVAILABLE === $m_video_archive->getArchiveStatus()) {
                $event_send = false;
            }
        }
    
        $ret = $this->updateByArchiveToken($video_archive, CVF::ARV_AVAILABLE, null, $url);
    
        /*if ($event_send) {
            $this->getServiceEvent()->recordAvailable($m_videoconf->getSubmissionId(), $video_archive);
        }*/
    
        return $ret;
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
    
        $res_video_no_upload = $this->getServiceVideoconfArchive()->getListVideoUpload();
    
        foreach ($res_video_no_upload as $m_video_archive) {
            try {
                $archive = json_decode($this->getServiceZOpenTok()->getArchive($m_video_archive->getArchiveToken()), true);
                if ($archive['status'] == CVF::ARV_AVAILABLE) {
                    $this->getServiceVideoconfArchive()->updateByArchiveToken($m_video_archive->getId(), CVF::ARV_UPLOAD, $archive['duration']);
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
     * @param integer   $conversation
     * @param string    $token
     *
     * @return integer
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
     * @return \Application\Service\Conversation
     */
    public function getServiceConversation()
    {
        return $this->getServiceLocator()->get('app_service_conversation');
    }
}