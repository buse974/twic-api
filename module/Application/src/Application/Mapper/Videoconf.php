<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Application\Model\Videoconf as CVF;
use Zend\Db\Sql\Predicate\Expression;

class Videoconf extends AbstractMapper
{
    public function getToken($id)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('token'))->where(array('id' => $id));

        return $this->selectWith($select);
    }

    public function get($id)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array(
            'id', 
            'title', 
            'description', 
            'conversation_id', 
            'submission_id', 
            'duration', 
            'videoconf$start_date' => new Expression("DATE_FORMAT(start_date, '%Y-%m-%dT%TZ') "), 
            'token', 
            'duration', 
            'archive_token', 
            'archive_link', 
            'archive_status', 
            'created_date', 
            'deleted_date'
        ))->where(array('videoconf.id' => $id));
   
        return $this->selectWith($select);
    }

    public function getBySubmission($submission)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('id', 'title', 'description', 'conversation_id', 'submission_id', 'duration', 
            'videoconf$start_date' => new Expression("DATE_FORMAT(start_date, '%Y-%m-%dT%TZ') "), 'token', 'archive_token', 'archive_link', 'archive_status', 
            'created_date', 'deleted_date'))->where(array('videoconf.submission_id' => $submission));

        return $this->selectWith($select);
    }

    public function getByVideoconfArchive($videoconf_archive)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('id', 'submission_id'))
            ->join('videoconf_archive', 'videoconf_archive.videoconf_id=videoconf.id', array())
            ->where(array('videoconf_archive.id' => $videoconf_archive));

        return $this->selectWith($select);
    }

    public function getRoom($token)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('id', 'title', 'description', 'duration', 'start_date', 'token', 'created_date'))
            ->join(array('videoconf_videoconf_entity' => 'videoconf_entity'), 'videoconf_videoconf_entity.videoconf_id=videoconf.id', array('id', 'name', 'token'))
            ->where(array('videoconf_videoconf_entity.token' => $token))
            ->where(array('deleted_date IS NULL'));

        return $this->selectWith($select);
    }

    public function getVideoconfTokenByTokenAdmin($token)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('id', 'token'))
            ->join('videoconf_admin', 'videoconf.id=videoconf_admin.videoconf_id', array())
            ->where(array('videoconf_admin.token' => $token));

        return $this->selectWith($select);
    }

    public function getListVideoUpload()
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('id', 'token', 'archive_token', 'archive_link', 'archive_status'))->where(array('archive_status' => CVF::ARV_STARTED));

        return $this->selectWith($select);
    }
}
