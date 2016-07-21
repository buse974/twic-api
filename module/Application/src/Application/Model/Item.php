<?php

namespace Application\Model;

use Application\Model\Base\Item as BaseItem;

class Item extends BaseItem
{
    const TYPE_LIVE_CLASS = 'LC';
    const TYPE_WORKGROUP = 'WG';
    const TYPE_INDIVIDUAL_ASSIGNMENT = 'IA';
    const TYPE_CAPSTONE_PROJECT = 'CP';
    const TYPE_POLL = 'POLL';
    const TYPE_DOCUMENT = 'DOC';
    const TYPE_TXT = 'TXT';
    const TYPE_MODULE = 'MOD';
    const TYPE_CHAT = 'CHAT';
    const TYPE_HANGOUT = 'HANGOUT';
    const TYPE_DISCUSSION = 'DISC';
    const TYPE_EQCQ = 'EQCQ';

    const CMP_VIDEOCONF = 'videoconf';
    const CMP_POLL = 'poll';
    const CMP_DOCUMENT = 'document';
    const CMP_SUB_DOCUMENT = 'subdocument';
    const CMP_TEXT_EDITOR = 'text_editor';
    const CMP_WHITEBOARD = 'whiteboard';
    const CMP_CHAT = 'chat';
    const CMP_DISCUSSION = 'thread';
    const CMP_EQCQ = 'eqcq';

    protected $materials;
    protected $module;
    protected $program;
    protected $course;
    protected $users;
    protected $new_message;
    protected $nbr_comment;
    protected $ct_done;
    protected $ct_rate;
    protected $ct_date;
    protected $ct_group;
    protected $opt_grading;
    protected $poll;
    protected $document;
    protected $videoconf;
    protected $thread;
    protected $submission;
    protected $submitted;
    protected $graded;
    protected $due;
    protected $library;
    protected $is_started;

    public function exchangeArray(array &$data)
    {
        parent::exchangeArray($data);

        $this->module = $this->requireModel('app_model_module', $data);
        $this->program = $this->requireModel('app_model_program', $data);
        $this->course = $this->requireModel('app_model_course', $data);
        $this->submission = $this->requireModel('app_model_submission', $data);
        $this->opt_grading = $this->requireModel('app_model_opt_grading', $data);
        $this->library = $this->requireModel('app_model_library', $data);
    }

    public function getIsStarted()
    {
        return $this->is_started;
    }

    public function setIsStarted($is_started)
    {
        $this->is_started = $is_started;

        return $this;
    }

    public function getLibrary()
    {
        return $this->library;
    }

    public function setLibrary($library)
    {
        $this->library = $library;

        return $this;
    }

    public function getDue()
    {
        return $this->due;
    }

    public function setDue($due)
    {
        $this->due = $due;

        return $this;
    }

    public function getGraded()
    {
        return $this->graded;
    }

    public function setGraded($graded)
    {
        $this->graded = $graded;

        return $this;
    }

    public function getSubmitted()
    {
        return $this->submitted;
    }

    public function setSubmitted($submitted)
    {
        $this->submitted = $submitted;

        return $this;
    }

    public function getSubmission()
    {
        return $this->submission;
    }

    public function setSubmission($submission)
    {
        $this->submission = $submission;

        return $this;
    }

    public function getThread()
    {
        return $this->thread;
    }

    public function setThread($thread)
    {
        $this->thread = $thread;

        return $this;
    }

    public function getDocument()
    {
        return $this->document;
    }

    public function setDocument($document)
    {
        $this->document = $document;

        return $this;
    }

    public function getVideoconf()
    {
        return $this->videoconf;
    }

    public function setVideoconf($videoconf)
    {
        $this->videoconf = $videoconf;

        return $this;
    }

    public function getPoll()
    {
        return $this->poll;
    }

    public function setPoll($poll)
    {
        $this->poll = $poll;

        return $this;
    }

    public function setMaterials($materials)
    {
        $this->materials = $materials;

        return $this;
    }

    public function getMaterials()
    {
        return $this->materials;
    }

    public function setModule($module)
    {
        $this->module = $module;

        return $this;
    }

    public function getModule()
    {
        return $this->module;
    }

    public function setProgram($program)
    {
        $this->program = $program;

        return $this;
    }

    public function getProgram()
    {
        return $this->program;
    }

    public function setCourse($course)
    {
        $this->course = $course;

        return $this;
    }

    public function getCourse()
    {
        return $this->course;
    }

    public function setUsers($users)
    {
        $this->users = $users;

        return $this;
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function setNewMessage($new_message)
    {
        $this->new_message = $new_message;

        return $this;
    }

    public function getNewMessage()
    {
        return $this->new_message;
    }

    public function getNbrComment()
    {
        return $this->nbr_comment;
    }

    public function setNbrComment($nbr_comment)
    {
        $this->nbr_comment = $nbr_comment;

        return $this;
    }

    public function getCtGroup()
    {
        return $this->ct_group;
    }

    public function setCtGroup($ct_group)
    {
        $this->ct_group = $ct_group;

        return $this;
    }

    public function getCtDate()
    {
        return $this->ct_date;
    }

    public function setCtDate($ct_date)
    {
        $this->ct_date = $ct_date;

        return $this;
    }

    public function getCtRate()
    {
        return $this->ct_rate;
    }

    public function setCtRate($ct_rate)
    {
        $this->ct_rate = $ct_rate;

        return $this;
    }

    public function getCtDone()
    {
        return $this->ct_done;
    }

    public function setCtDone($ct_done)
    {
        $this->ct_done = $ct_done;

        return $this;
    }

    public function getOptGrading()
    {
        return $this->opt_grading;
    }

    public function setOptGrading($opt_grading)
    {
        $this->opt_grading = $opt_grading;

        return $this;
    }
}
