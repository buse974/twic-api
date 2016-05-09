<?php

namespace Application\Service;

use Dal\Service\AbstractService;
use DateTimeZone;
use DateTime;
use Application\Model\Videoconf as CVF;
use OpenTok\Role as OpenTokRole;
use Application\Model\Role as ModelRole;
use Application\Model\Item as ModelItem;
use Application\Model\Conversation as ModelConversation;

class Videoconf extends AbstractService
{
    /**
     * @invokable
     *
     * @param string $title
     * @param string $description
     * @param string $start_date
     * @param integer $submission_id
     * @param integer $item_id
     * @param integer $conversation
     * @throws \Exception
     */
    public function add($title, $description, $start_date, $submission_id = null, $item_id = null, $conversation = null)
    {
        if(null === $submission_id && null !==$item_id) {
            $submission_id = $this->getServiceSubmission()->getByItem($item_id)->getId();
        }
        
        $m_videoconf = $this->getModel();
        $m_videoconf->setTitle($title)
            ->setDescription($description)
            ->setSubmissionId($submission_id)
            ->setConversationId($conversation)
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
        $m_videoconf->setVideoconfInvitation($this->getServiceVideoConfInvitation()
            ->getByVideoconfId($m_videoconf->getId())
            ->toArray());

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
            ->setStartDate($start_date);

        if ($start_date !== null && $m_videoconf_tmp->getStartDate() !== $start_date) {
            $res_videoconf_invitation = $this->getServiceVideoConfInvitation()->getByVideoconfId($id);
            if ($res_videoconf_invitation->count() > 0) {
                foreach ($res_videoconf_invitation as $m_videoconf_invitation) {
                    $this->getServiceMail()->sendTpl('tpl1', $m_videoconf_invitation->getEmail(), array('firstname' => $m_videoconf_invitation->getFirstname(), 'lastname' => $m_videoconf_invitation->getLastname(), 'link' => $this->getServiceLocator()
                        ->get('config')['path_videoconf_guest'].$m_videoconf_tmp->getToken(), 'start_date' => (new DateTime($start_date, new DateTimeZone('UTC')))->setTimezone(new DateTimeZone($m_videoconf_invitation->getUtc()))
                        ->format('Y-m-d H:i:s'), ));
                }
            }
        }

        return $this->getMapper()->update($m_videoconf);
    }

    /**
     * Update Video Conf.
     *
     * @invokable
     *
     * @param int    $item_prog
     * @param string $start_date
     *
     * @return int
     */
    public function updateByItemProg($item_prog, $start_date)
    {
        $m_videoconf = $this->getModel();
        $m_videoconf->setStartDate($start_date);

        return $this->getMapper()->update($m_videoconf, array('item_prog_id' => $item_prog));
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

        return array('count' => $mapper->count(),'results' => $res_videoconf);
    }

    /**
     * Admin join video conf.
     *
     * @invokable
     *
     * @param string $token
     */
    public function join($id)
    {
        return $this->get($id)->setVideoconfAdmin($this->getServiceVideoconfAdmin()
            ->add($id));
    }

    /**
     * Get videoconf.
     *
     * @invokable
     *
     * @param integer $submission
     *
     * @return \Application\Model\Videoconf
     */
    public function getBySubmission($submission)
    {
        $res_videoconf = $this->getMapper()->getBySubmission($submission);

        if ($res_videoconf->count() === 0) {
            return null;
        }

        $m_videoconf = $res_videoconf->current();
        $m_videoconf->setVideoconfArchives($this->getServiceVideoconfArchive()->getListRecordBySubmission($submission));

        return $m_videoconf;
    }
    
    /**
     * @param integer $submission_id
     * 
     * @return \Application\Model\Videoconf
     */
    public function getListOrCreate($submission_id)
    {
        $m_videoconf = $this->getBySubmission(null, $submission_id);
        if(null === $m_videoconf) {
            $this->add(null, null, null, $submission_id);
            $m_videoconf = $this->getBySubmission($submission_id);
        }
        
        return $m_videoconf;
    }

    /**
     * Get videoconf by videoconf archive.
     
     * @param int $videoconf_archive
     *
     * @throws \Exception
     *
     * @return \Application\Model\Videoconf
     */
    public function getByVideoconfArchive($videoconf_archive)
    {
        return $this->getMapper()->getByVideoconfArchive($videoconf_archive)->current();
    }
    
    /**
     * @invokable
     *
     * @param int $id
     * @param int $submission
     *
     * @throws \Exception
     */
    public function joinUser($id = null, $submission = null)
    {
        if (null !== $id) {
            $res_videoconf = $this->getMapper()->get($id);
            $m_videoconf = $res_videoconf->current();
        } elseif (null !== $submission) {
            $m_videoconf = $this->getListOrCreate($submission);
        } else {
            throw new \Exception('Error params joinUser');
        }
        if (null === $m_videoconf) {
            throw new \Exception('Error select');
        }
        if(null === $submission) {
            $submission = $m_videoconf->getSubmissionId();
        }
        
        $identity = $this->getServiceUser()->getIdentity();
        
        $m_item = $this->getServiceItem()->getBySubmission($submission);
        $res=[];
        if ($m_item->getType() !== ModelItem::TYPE_WORKGROUP) {
            $instructors = $this->getServiceUser()->getList(array(), ModelRole::ROLE_INSTRUCTOR_STR, null, $m_item->getCourseId());
            foreach ($instructors['list'] as $instructor) {
                $res[$instructor['id']] = $instructor;
            }
        }
        
        $convs = $this->getServiceConversation()->getListOrCreate($submission); 
        $finalconv = null;
        foreach ($convs as $conv) {
            if($conv->getName()===ModelConversation::DEFAULT_NAME) {
                $finalconv=$conv->getId();
                break;
            }
        }
        
        $m_videoconf->setDocs($this->getServiceVideoconfDoc()->getListByVideoconf($m_videoconf->getId()))
            ->setConversationId($finalconv)
            ->setInstructors($res)
            ->setVideoconfAdmin(
                $this->getServiceVideoconfAdmin()->add(
                    $m_videoconf->getId(), 
                    (!array_key_exists(ModelRole::ROLE_STUDENT_ID, $identity['roles'])) ?OpenTokRole::MODERATOR : OpenTokRole::PUBLISHER));
    
        return $m_videoconf;
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
            $this->getServiceVideoconfArchive()->add($videoconf->getId(), $arr_archive['id']);
        }

        return true;
    }

    /**
     * Start record video conf.
     *
     * @invokable
     *
     * @param int $id
     */
    public function startRecord($id)
    {
        $m_videoconf = $this->get($id);

        $arr_archive = json_decode($this->getServiceZOpenTok()->startArchive($m_videoconf->getToken()), true);

        if ($arr_archive['status'] == 'started') {
            $this->getServiceVideoconfArchive()->add($m_videoconf->getId(), $arr_archive['id']);
        }

        return $arr_archive;
    }

    /**
     * Stop record video conf.
     *
     * @invokable
     *
     * @param int $id
     */
    public function stopRecord($id)
    {
        $m_videoconf_archive = $this->getServiceVideoconfArchive()->getLastArchiveId($id);

        return $this->getServiceZOpenTok()->stopArchive($m_videoconf_archive->getArchiveToken());
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

        foreach ($res_video_no_upload as $m_videoconf_archive) {
            try {
                $archive = json_decode($this->getServiceZOpenTok()->getArchive($m_videoconf_archive->getArchiveToken()), true);
                if ($archive['status'] == CVF::ARV_AVAILABLE) {
                    $this->getServiceVideoconfArchive()->updateByArchiveToken($m_videoconf_archive->getId(), CVF::ARV_UPLOAD, $archive['duration']);
                    $arr = $m_videoconf_archive->toArray();
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
     * @param int    $videoconf_archive
     * @param string $url
     *
     * @return int
     */
    public function validTransfertVideo($videoconf_archive, $url)
    {
        $event_send = true;
        $m_videoconf = $this->getByVideoconfArchive($videoconf_archive);
        $res_videoconf_archive = $this->getServiceVideoconfArchive()->getListByVideoConf($m_videoconf->getId());
        
        foreach ($res_videoconf_archive as $m_videoconf_archive) {
            if(CVF::ARV_AVAILABLE===$m_videoconf_archive->getArchiveStatus()) {
                $event_send=false;
            }
        }
        
        $ret = $this->getServiceVideoconfArchive()->updateByArchiveToken($videoconf_archive, CVF::ARV_AVAILABLE, null, $url);
        
        if($event_send) {
            $this->getServiceEvent()->recordAvailable($m_videoconf->getSubmissionId(),$videoconf_archive);
        }
        
        return $ret;
    }

    /**
     * @invokable
     *
     * @param int   $videoconf
     * @param array $users
     */
    public function addConversation($videoconf, $users, $text)
    {
        $user = $this->getServiceUser()->getIdentity();
        if (!in_array($user['id'], $users)) {
            $users[] = $user['id'];
        }

        $conversation = $this->getServiceConversationUser()->createConversation($users);
        $this->getServiceVideoconfConversation()->add($conversation, $videoconf);

        return $this->getServiceMessage()->sendVideoConf($text, null, $conversation);
    }
    
    /**
     * @invokable 
     * 
     * @param integer $item_id
     */
    public function getByItem($item_id)
    {
        $ar_submission = $this->getServiceSubmission()->get($item_id)->toArray();
        $ar_submission = $ar_submission + $this->getServiceSubmission()->getContent($ar_submission['id']);
        
        return $ar_submission;
    }

    /**
     * @invokable
     * 
     * @param int $item_prog
     */
    public function start($item_prog)
    {
        return $this->getServiceItemProgUser()->start($item_prog);
    }

    /**
     * @invokable
     *
     * @param int $item_prog
     */
    public function end($item_prog)
    {
        return $this->getServiceItemProgUser()->end($item_prog);
    }

    /**
     * @return \Application\Service\Submission
     */
    public function getServiceSubmission()
    {
        return $this->getServiceLocator()->get('app_service_submission');
    }

    /**
     * @return \Application\Service\VideoconfArchive
     */
    public function getServiceVideoconfArchive()
    {
        return $this->getServiceLocator()->get('app_service_videoconf_archive');
    }

    /**
     * @return \Application\Service\VideoconfInvitation
     */
    public function getServiceVideoConfInvitation()
    {
        return $this->getServiceLocator()->get('app_service_videoconf_invitation');
    }

    /**
     * @return \Application\Service\ConversationUser
     */
    public function getServiceConversationUser()
    {
        return $this->getServiceLocator()->get('app_service_conversation_user');
    }

    /**
     * @return \Application\Service\Conversation
     */
    public function getServiceConversation()
    {
        return $this->getServiceLocator()->get('app_service_conversation');
    }

    /**
     * @return \Application\Service\VideoconfDoc
     */
    public function getServiceVideoconfDoc()
    {
        return $this->getServiceLocator()->get('app_service_videoconf_doc');
    }

    /**
     * @return \Application\Service\VideoconfConversation
     */
    public function getServiceVideoconfConversation()
    {
        return $this->getServiceLocator()->get('app_service_videoconf_conversation');
    }

    /**
     * @return \Application\Service\VideoconfAdmin
     */
    public function getServiceVideoconfAdmin()
    {
        return $this->getServiceLocator()->get('app_service_videoconf_admin');
    }

    /**
     * @return \Application\Service\User
     */
    public function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }

    /**
     * @return \Application\Service\Event
     */
    public function getServiceEvent()
    {
        return $this->getServiceLocator()->get('app_service_event');
    }

    /**
     * @return \Application\Service\Message
     */
    public function getServiceMessage()
    {
        return $this->getServiceLocator()->get('app_service_message');
    }

    /**
     * @return \Application\Service\ItemAssignment
     */
    public function getServiceItemAssignment()
    {
        return $this->getServiceLocator()->get('app_service_item_assignment');
    }

    /**
     * @return \Application\Service\Item
     */
    public function getServiceItem()
    {
        return $this->getServiceLocator()->get('app_service_item');
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
