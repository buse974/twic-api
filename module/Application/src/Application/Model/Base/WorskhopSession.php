<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class WorskhopSession extends AbstractModel
{
    protected $id;
    protected $start_date;
    protected $videoconf_groupwork;
    protected $videoconf_ptop;

    protected $prefix = 'worskhop_session';

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getStartDate()
    {
        return $this->start_date;
    }

    public function setStartDate($start_date)
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getVideoconfGroupwork()
    {
        return $this->videoconf_groupwork;
    }

    public function setVideoconfGroupwork($videoconf_groupwork)
    {
        $this->videoconf_groupwork = $videoconf_groupwork;

        return $this;
    }

    public function getVideoconfPtop()
    {
        return $this->videoconf_ptop;
    }

    public function setVideoconfPtop($videoconf_ptop)
    {
        $this->videoconf_ptop = $videoconf_ptop;

        return $this;
    }
}
