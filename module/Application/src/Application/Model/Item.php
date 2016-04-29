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
    const TYPE_DISCUSSION = 'DISC';
    const TYPE_EQCQ = 'EQCQ';
    
    const CMP_VIDEOCONF = 'videoconf';
    const CMP_POLL = 'poll';
    const CMP_DOCUMENT = 'document';
    const CMP_SUB_DOCUMENT = 'subdocument';
    const CMP_TEXT_EDITOR = 'text_editor';
    const CMP_CHAT = 'chat';
    const CMP_DISCUSSION = 'discussion';
    const CMP_EQCQ = 'eqcq';
    
    protected $materials;
    protected $module;
    protected $program;
    protected $course;
    protected $item_assignment;
    protected $users;
    protected $item_grade;
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
      
    public function exchangeArray(array &$data)
    {
        parent::exchangeArray($data);

        $this->module = $this->requireModel('app_model_module', $data);
        $this->program = $this->requireModel('app_model_program', $data);
        $this->course = $this->requireModel('app_model_course', $data);
        $this->item_prog = $this->requireModel('app_model_item_prog', $data);
        $this->item_assignment = $this->requireModel('app_model_item_assignment', $data);
        $this->item_grade = $this->requireModel('app_model_item_grading', $data);
        $this->opt_grading = $this->requireModel('app_model_opt_grading', $data);
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

    public function setItemProg($item_prog)
    {
        $this->item_prog = $item_prog;

        return $this;
    }

    public function getItemProg()
    {
        return $this->item_prog;
    }

    public function setItemAssignment($item_assignment)
    {
        $this->item_assignment = $item_assignment;

        return $this;
    }

    public function getItemAssignment()
    {
        return $this->item_assignment;
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

    public function setItemGrade($item_grade)
    {
        $this->item_grade = $item_grade;

        return $this;
    }

    public function getItemGrade()
    {
        return $this->item_grade;
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
