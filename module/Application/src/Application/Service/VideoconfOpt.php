<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class VideoconfOpt extends AbstractService
{
    /**
     *
     * @param integer $item_id
     * @param bool $record
     * @param integer $nb_user_autorecord
     * @param bool $allow_intructor
     *
     * @return integer
     */
    public function add($item_id, $record, $nb_user_autorecord, $allow_intructor)
    {
        $m_opt_videoconf = $this->getModel()
            ->setItemId($item_id)
            ->setRecord($record)
            ->setNbUserAutorecord($nb_user_autorecord)
            ->setAllowIntructor($allow_intructor);
    
        return $this->getMapper()->insert($m_opt_videoconf);
    }
    
    public function getByItem($item_id)
    {
        $m_opt_videoconf = $this->getModel()->setItemId($item_id);
        
        $res_opt_videoconf = $this->getMapper()->select($m_opt_videoconf);
        
        return ($res_opt_videoconf->count() >0) ? $res_opt_videoconf->current():null;
    }
}