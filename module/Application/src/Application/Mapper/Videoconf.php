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
            'deleted_date',
        ))->where(array('videoconf.id' => $id));

        return $this->selectWith($select);
    }

    public function getBySubmission($submission)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('id', 'title', 'description', 'conversation_id', 'submission_id', 'duration',
            'videoconf$start_date' => new Expression("DATE_FORMAT(start_date, '%Y-%m-%dT%TZ') "), 'token', 'archive_token', 'archive_link', 'archive_status',
            'created_date', 'deleted_date', ))->where(array('videoconf.submission_id' => $submission));

        return $this->selectWith($select);
    }

    public function getByVideoconfArchive($video_archive)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('id', 'submission_id'))
            ->join('video_archive', 'video_archive.videoconf_id=videoconf.id', array())
            ->where(array('video_archive.id' => $video_archive));

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
    public function getListId($school_id, $program_id = null, $course_id = null, $item_id = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id'])->join('submission', 'submission.id = videoconf.submission_id', [])
                   ->join('item', 'item.id = submission.item_id', [])
                   ->join('course', 'course.id = item.course_id', [])
                   ->join('program', 'program.id = course.program_id', [])
                    ->where(['program.school_id' => $school_id]);
        if (null !== $item_id) {
            $select->where(['item.id' => $item_id]);
        } elseif (null !== $course_id) {
            $select->where(['course.id' => $course_id]);
        } elseif (null !== $program_id) {
            $select->where(['program.id' => $program_id]);
        }

        return $this->selectWith($select);
    }
}
