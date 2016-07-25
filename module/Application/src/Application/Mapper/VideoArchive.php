<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Application\Model\VideoArchive as CVF;

class VideoArchive extends AbstractMapper
{
    public function getList($submission_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'archive_token', 'archive_link', 'archive_status', 'archive_duration', 'conversation_id', 'created_date'))
            ->join('sub_conversation', 'sub_conversation.conversation_id=video_archive.conversation_id', ['video_archive$submission_id' => 'submission_id'])
            ->where(['sub_conversation.submission_id' => $submission_id])
            ->where(['archive_status' => CVF::ARV_AVAILABLE]);
        
        return $this->selectWith($select);
    }
    
    public function get($id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'archive_token', 'archive_link', 'archive_status', 'archive_duration', 'conversation_id', 'created_date'))
            ->join('sub_conversation', 'sub_conversation.conversation_id=video_archive.conversation_id', ['video_archive$submission_id' => 'submission_id'])
            ->where(['video_archive.id' => $id]);
    
        return $this->selectWith($select);
    }
    
    public function getListSameConversation($video_archive)
    {
        $sub_select = $this->tableGateway->getSql()->select();
        $sub_select->columns(['conversation_id'])
                  ->where(['id' => $video_archive]);
        
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id','archive_status','conversation_id'))
        ->join('sub_conversation', 'sub_conversation.conversation_id=video_archive.conversation_id', ['video_archive$submission_id' => 'submission_id'])
        ->where(['video_archive.conversation_id' => $sub_select]);
    
        return $this->selectWith($select);
    }
    
    /**
     * @param int $conversation_id
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getLastArchiveId($conversation_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'archive_token', 'archive_link', 'archive_status', 'archive_duration', 'conversation_id', 'created_date'))
            ->where(['video_archive.conversation_id' => $conversation_id])
            ->order('video_archive.created_date DESC')
            ->limit(1);
        
        return $this->selectWith($select);
    }
    
    public function getListVideoUpload()
    {
        $select = $this->tableGateway->getSql()->select();
    
        $select->columns(array('id', 'archive_token', 'archive_link', 'archive_status'))->where(array('archive_status' => CVF::ARV_STARTED));
    
        return $this->selectWith($select);
    }
}