<?php

namespace Application\Service;

use Dal\Service\AbstractService;
use Dal\Db\ResultSet\ResultSet;

class Thread extends AbstractService
{
    /**
     * Add thread.
     *
     * @invokable
     *
     * @param string $title
     * @param int    $course
     *
     * @throws \Exception
     *
     * @return int
     */
    public function add($title, $course)
    {
        $m_thread = $this->getModel()
                         ->setCourseId($course)
                         ->setTitle($title)
                         ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'))
                         ->setUserId($this->getServiceAuth()->getIdentity()->getId());

        if ($this->getMapper()->insert($m_thread) <= 0) {
            throw new \Exception('error insert thread');
        }

        return $this->getMapper()->getLastInsertValue();
    }

    /**
     * update thread.
     *
     * @invokable
     *
     * @TODO Add updated date
     *
     * @param int    $id
     * @param string $title
     *
     * @return int
     */
    public function update($id, $title)
    {
        $m_thread = $this->getModel()
                         ->setTitle($title);
                         //->setUpdatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));

        return $this->getMapper()->update($m_thread, array('id' => $id, 'user_id' => $this->getServiceAuth()->getIdentity()->getId()));
    }

    /**
     * GetList  Thread by course.
     *
     * @invokable
     *
     * @param int $course
     *
     * @throws \Exception
     *
     * @return ResultSet
     */
    public function getList($course)
    {
        $res_thread = $this->getMapper()->getList($course);

        if ($res_thread->count() <= 0) {
            throw new \Exception('not thread with course id: '.$course);
        }

        return $res_thread;
    }

    /**
     * delete thread.
     *
     * @invokable
     *
     * @param int $id
     */
    public function delete($id)
    {
        return $this->getMapper()->update(
                $this->getModel()->setDeletedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s')),
                array('user_id' => $this->getServiceAuth()->getIdentity()->getId(), 'id' => $id));
    }

    /**
     * @return \Auth\Service\AuthService
     */
    public function getServiceAuth()
    {
        return $this->getServiceLocator()->get('auth.service');
    }
}
