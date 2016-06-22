<?php

namespace Application\Model;

use Application\Model\Base\Videoconf as BaseVideoconf;

class Videoconf extends BaseVideoconf
{
    const FINISHED = 'finished';
    const ONGOING = 'ongoing';
    const NOTSTARTED = 'notstarted';

    const ARV_AVAILABLE = 'available';
    const ARV_EXPIRED = 'expired';
    const ARV_FAILED = 'failed';
    const ARV_STARTED = 'started';
    const ARV_STOPPED = 'stopped';
    const ARV_UPLOAD = 'uploaded';

    protected $videoconf_entity;
    protected $videoconf_invitation;
    protected $videoconf_admin;
    protected $video_archives;
    protected $conversations;
    protected $users;
    protected $instructors;
    protected $docs;
    protected $item_assignment_id;

    public function exchangeArray(array &$data)
    {
        parent::exchangeArray($data);

        $this->videoconf_invitation = $this->requireModel('app_model_videoconf_invitation', $data);
        $this->videoconf_admin = $this->requireModel('app_model_videoconf_admin', $data);
        $this->videoconf_entity = $this->requireModel('app_model_videoconf_entity', $data);
    }

    public function getInstructors()
    {
        return $this->instructors;
    }

    public function setInstructors($instructors)
    {
        $this->instructors = $instructors;

        return $this;
    }

    public function getItemAssignmentId()
    {
        return $this->item_assignment_id;
    }

    public function setItemAssignmentId($item_assignment_id)
    {
        $this->item_assignment_id = $item_assignment_id;

        return $this;
    }

    public function getVideoconfArchives()
    {
        return $this->video_archives;
    }

    public function setVideoconfArchives($video_archives)
    {
        $this->video_archives = $video_archives;

        return $this;
    }

    public function getDocs()
    {
        return $this->docs;
    }

    public function setDocs($docs)
    {
        $this->docs = $docs;

        return $this;
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function setUsers($users)
    {
        $this->users = $users;

        return $this;
    }

    public function getConversations()
    {
        return $this->conversations;
    }

    public function setConversations($conversations)
    {
        $this->conversations = $conversations;

        return $this;
    }

    public function setVideoconfEntity($videoconf_entity)
    {
        $this->videoconf_entity = $videoconf_entity;

        return $this;
    }

    public function getVideoconfEntity()
    {
        return $this->videoconf_entity;
    }

    public function setVideoconfInvitation($videoconf_invitation)
    {
        $this->videoconf_invitation = $videoconf_invitation;

        return $this;
    }

    public function getVideoconfInvitation()
    {
        return $this->videoconf_invitation;
    }

    public function setVideoconfAdmin($videoconf_admin)
    {
        $this->videoconf_admin = $videoconf_admin;

        return $this;
    }

    public function getVideoconfAdmin()
    {
        return $this->videoconf_admin;
    }
}
