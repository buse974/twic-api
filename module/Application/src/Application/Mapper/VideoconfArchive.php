<?php
namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Application\Model\Videoconf as CVF;

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
        
        $select->columns(array('id','archive_link','archive_token','archive_duration'))
            ->join('videoconf', 'videoconf.id=videoconf_archive.videoconf_id', array(), $select::JOIN_INNER)
            ->where(array('videoconf.item_prog_id' => $item_prog))
            ->where(array('videoconf_archive.archive_status' => CVF::ARV_AVAILABLE));
        
        return $this->selectWith($select);
    }

    /**
     *
     * @param int $videoconf            
     *
     * @return \Application\Model\VideoconfArchive
     */
    public function getLastArchiveId($videoconf)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->columns(array('id','archive_link','archive_token','archive_duration'))
            ->where(array('videoconf_archive.videoconf_id' => $videoconf))
            ->order(array('videoconf_archive.id' => 'DESC'))
            ->limit(1);
        
        return $this->selectWith($select)->current();
    }
    
    /**
     *
     * @param int $videoconf
     *
     * @return \Application\Model\VideoconfArchive
     */
    public function getListByVideoConf($videoconf)
    {
        $select = $this->tableGateway->getSql()->select();
    
        $select->columns(array('id','archive_link','archive_token','archive_duration'))
            ->where(array('videoconf_archive.videoconf_id' => $videoconf))
            ->order(array('videoconf_archive.id' => 'ASC'));
    
        return $this->selectWith($select)->current();
    }
}
