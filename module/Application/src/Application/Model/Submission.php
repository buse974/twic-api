<?php

namespace Application\Model;

use Application\Model\Base\Submission as BaseSubmission;

class Submission extends BaseSubmission
{
    protected $chat;
    protected $submission_user;
    protected $video_archives;
    protected $users;
    protected $item;
    protected $thread_id;
    protected $submission_pg;

    public function exchangeArray(array &$data)
    {
        parent::exchangeArray($data);

        $this->submission_user = $this->requireModel('app_model_submission_user', $data);
        $this->item = $this->requireModel('app_model_item', $data);
        $this->submission_pg = $this->requireModel('app_model_submission_pg', $data);
    }

    public function getThreadId()
    {
        return $this->thread_id;
    }

    public function setThreadId($thread_id)
    {
        $this->thread_id = $thread_id;

        return $this;
    }

    public function getSubmissionPg()
    {
        return $this->submission_pg;
    }

    public function setSubmissionPg($submission_pg)
    {
        $this->submission_pg = $submission_pg;

        return $this;
    }

    public function getItem()
    {
        return $this->item;
    }

    public function setItem($item)
    {
        $this->item = $item;

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

    public function getVideoconfArchives()
    {
        return $this->video_archives;
    }

    public function setVideoconfArchives($video_archives)
    {
        $this->video_archives = $video_archives;

        return $this;
    }

    public function getSubmissionUser()
    {
        return $this->submission_user;
    }

    public function setSubmissionUser($submission_user)
    {
        $this->submission_user = $submission_user;

        return $this;
    }

    public function getChat()
    {
        return $this->chat;
    }

    public function setChat($chat)
    {
        $this->chat = $chat;

        return $this;
    }
}
