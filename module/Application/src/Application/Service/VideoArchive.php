<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Archive Video
 */
namespace Application\Service;

use Dal\Service\AbstractService;
use Application\Model\VideoArchive as CVF;

/**
 * Class VideoArchive.
 */
class VideoArchive extends AbstractService
{
    /**
     * Get List Video.
     *
     * @invokable
     *
     * @param int $item_id
     *
     * @return array|stdClass
     */
    public function getList($item_id)
    {
        $res_videoconf_archive = $this->getMapper()->getList($item_id);
        $tmp = $ret = [];
        foreach ($res_videoconf_archive as $m_videoconf_archive) {
            $tmp[$m_videoconf_archive->getConversationId()][] = $m_videoconf_archive;
        }

        foreach ($tmp as $k => $v) {
            $ret[] = [
             'conversation_id' => $k,
             'videos' => $v,
             'conversation_user' => $this->getServiceConversationUser()->getUserByConversation($k), ];
        }

        return $ret;
    }

    /**
     * Get Video.
     *
     * @param int $id
     *
     * @return \Application\Model\VideoArchive
     */
    public function get($id)
    {
        $res_videoconf_archive = $this->getMapper()->get($id);
        if ($res_videoconf_archive->count() <= 0) {
            throw new \Exception('video_conf');
        }

        return $res_videoconf_archive->current();
    }

    /**
     * Start record video conf.
     *
     * @invokable
     *
     * @param interger $conversation_id
     *
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
     *
     * @return mixed
     */
    public function stopRecord($conversation_id)
    {
        $res_video_archive = $this->getMapper()->getLastArchiveId($conversation_id);
        if ($res_video_archive->count() <= 0) {
            throw new \Exception('no video with conversation: '.$conversation_id);
        }
        $m_video_archive = $res_video_archive->current();

        return $this->getServiceZOpenTok()->stopArchive($m_video_archive->getArchiveToken());
    }

    /**
     * Update Status Video.
     *
     * @param string $token
     * @param string $status
     * @param int    $duration
     * @param string $link
     *
     * @return int
     */
    public function updateByArchiveToken($archive_token, $status, $duration = null, $link = null)
    {
        $m_video_archive = $this->getModel();
        $m_video_archive->setArchiveDuration($duration)
        ->setArchiveStatus($status)
        ->setArchiveLink($link);
    
        return $this->getMapper()->update($m_video_archive, ['archive_token' => $archive_token]);
    }
    
    /**
     * Valide the video transfer
     *
     * @param array $json
     */
    public function checkStatus($json)
    {
        $ret = false;
        if ($json['status'] == 'uploaded') {
            $ret = $this->updateByArchiveToken($json['id'], CVF::ARV_AVAILABLE, null, $json['link']);
            if ($ret) {
                $m_video_archive = $this->getMapper()->select($this->getModel()->setArchiveToken($json['id']))->current();
                $m_conversation = $this->getServiceConversation()->getLite($m_video_archive->getConversationId());
                $m_conversation_opt = $this->getServiceConversationOpt()->get($m_conversation->getConversationOptId());
                
                $item_id = null;
                if (null !== $m_conversation_opt) {
                    $item_id = $m_conversation_opt->getItemId();
                }
                
                $m_course = $this->getServiceCourse()->getByItem($item_id);
                $m_inst = $this->getServiceUser()->getListIdInstructorByItem($item_id);
                $m_user = $this->getServiceUser()->getListIdByConversation($m_conversation->getId());
                
                $miid = [];
                foreach (array_merge($m_inst, $m_user) as $u_id) {
                    $miid[] = 'M'.$u_id;
                }
                
                $this->getServicePost()->addSys('VCONV'.$m_conversation->getId(), '', [
                    'item' => $item_id,
                    'course' => $m_course->getId(),
                    'conversation' => $m_conversation->getId(),
                    'link' => $json['link']
                ], 'create', $miid/*sub*/,
                    null/*parent*/,
                    null/*page*/,
                    null/*org*/,
                    null/*user*/,
                    $m_course->getId()/*course*/,
                    'video');
            }
        }
        
        return $ret;
    }
    
    /**
     * Add Video.
     *
     * @param int    $conversation
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
     * Get Service Conversation.
     *
     * @return \Application\Service\Conversation
     */
    private function getServiceConversation()
    {
        return $this->container->get('app_service_conversation');
    }

    /**
     * Get Service Conversation Opt
     *
     * @return \Application\Service\ConversationOpt
     */
    private function getServiceConversationOpt()
    {
        return $this->container->get('app_service_conversation_opt');
    }
    
    /**
     * Get Service Item
     *
     * @return \Application\Service\Item
     */
    private function getServiceItem()
    {
        return $this->container->get('app_service_item');
    }
    
    /**
     * Get Service Conversation user.
     *
     * @return \Application\Service\ConversationUser
     */
    private function getServiceConversationUser()
    {
        return $this->container->get('app_service_conversation_user');
    }

    /**
     * Get Service OpenTok.
     *
     * @return \ZOpenTok\Service\OpenTok
     */
    private function getServiceZOpenTok()
    {
        return $this->container->get('opentok.service');
    }

    /**
     * Get Service Post
     *
     * @return \Application\Service\Post
     */
    private function getServicePost()
    {
        return $this->container->get('app_service_post');
    }
    
    /**
     * Get Service Course
     *
     * @return \Application\Service\Course
     */
    private function getServiceCourse()
    {
        return $this->container->get('app_service_course');
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
}
