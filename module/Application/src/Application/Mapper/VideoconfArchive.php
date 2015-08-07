<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class VideoconfArchive extends AbstractMapper
{
    public function getListVideoUpload()
    {
        $select = $this->tableGateway->getSql()->select();
    
        $select->columns(array('id','archive_token','archive_link','archive_status'))->where(array('archive_status' => CVF::ARV_STARTED));
    
        return $this->selectWith($select);
    }
    
    public function getListRecordByItemProg($item_prog)
    {
        $select = $this->tableGateway->getSql()->select();
    
        $select->columns(array('id', 'archive_link', 'archive_token', 'archive_duration'))
        ->join('videoconf', 'videoconf.id=videoconf_archive.videoconf_id', array(), $select::JOIN_INNER)
        ->where(array('videoconf.item_prog_id' => $item_prog));

        return $this->selectWith($select);
    }
}