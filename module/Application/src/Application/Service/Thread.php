<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Thread
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class Thread.
 */
class Thread extends AbstractService
{
    /**
     * Add thread.
     *
     * @invokable
     *
     * @param string $title
     * @param int    $course
     * @param string $message
     * @param int    $item_id
     *
     * @throws \Exception
     *
     * @return int
     */
    public function add($title, $course, $message = null, $item_id = null)
    {
        $m_thread = $this->getModel()
            ->setCourseId($course)
            ->setTitle($title)
            ->setItemId($item_id)
            ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'))
            ->setUserId($this->getServiceUser()
            ->getIdentity()['id']);

        if ($this->getMapper()->insert($m_thread) <= 0) {
            throw new \Exception('error insert thread');
        }

        $id = $this->getMapper()->getLastInsertValue();
        $this->getServiceEvent()->threadNew($id);

        if (null !== $message) {
            $id = $this->getServiceThreadMessage()->add($message, $id, true);
        }

        return $id;
    }

    /**
     * update thread.
     *
     * @invokable
     *
     * @todo Add updated date
     *      
     * @param int    $id
     * @param string $title
     * @param int    $item_id
     *
     * @return int
     */
    public function update($id, $title = null, $item_id = null)
    {
        if ($item_id === null && $title === null) {
            return 0;
        }

        $m_thread = $this->getModel()
            ->setId($id)
            ->setTitle($title)
            ->setItemId($item_id);

        return $this->getMapper()->update($m_thread);
    }

    /**
     * GetList Thread by course.
     *
     * @invokable
     *
     * @param int    $course
     * @param array  $filter
     * @param string $name
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getList($course, $filter = null, $name = null)
    {
        $mapper = $this->getMapper();
        $res_thread = $mapper->usePaginator($filter)->getList($course, null, $name);
        foreach ($res_thread as $m_thread) {
            $m_thread->setMessage($this->getServiceThreadMessage()
                ->getLast($m_thread->getId()));
            $roles = [];
            foreach ($this->getServiceRole()->getRoleByUser($m_thread->getUser()
                ->getId()) as $role) {
                $roles[] = $role->getName();
            }
            $m_thread->getUser()->setRoles($roles);
        }

        return array('count' => $mapper->count(), 'list' => $res_thread);
    }

    /**
     * Get By submission.
     *
     * @param int $submission_id
     *
     * @return void|\Application\Model\Thread
     */
    public function getBySubmission($submission_id)
    {
        $res_thread = $this->getMapper()->getList(null, null, null, $submission_id);
        if ($res_thread->count() <= 0) {
            return;
        }

        $m_thread = $res_thread->current();
        $m_thread->setMessage($this->getServiceThreadMessage()
            ->getLast($m_thread->getId()));

        return $m_thread;
    }

    /**
     * Get Thread.
     *
     * @invokable
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return \Application\Model\Thread
     */
    public function get($id)
    {
        $mapper = $this->getMapper();
        $res_thread = $mapper->getList(null, $id);

        if ($res_thread->count() <= 0) {
            throw new \Exception('not thread with id: '.$id);
        }

        $m_thread = $res_thread->current();
        $m_thread->setMessage($this->getServiceThreadMessage()
            ->getLast($m_thread->getId()));
        $roles = [];
        foreach ($this->getServiceRole()->getRoleByUser($m_thread->getUser()
            ->getId()) as $role) {
            $roles[] = $role->getName();
        }
        $m_thread->getUser()->setRoles($roles);

        return $m_thread;
    }

    /**
     * Get By Item.
     *
     * @param int $item_id
     *
     * @return \Application\Model\Thread|null
     */
    public function getByItem($item_id)
    {
        $res_thread = $this->getMapper()->select($this->getModel()
            ->setItemId($item_id));

        return ($res_thread->count() > 0) ? $res_thread->current() : null;
    }

    /**
     * delete thread.
     *
     * @invokable
     *
     * @param int $id
     *
     * @return int
     */
    public function delete($id)
    {
        return $this->getMapper()->update($this->getModel()
            ->setDeletedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s')), array('user_id' => $this->getServiceUser()
            ->getIdentity()['id'], 'id' => $id, ));
    }

    /**
     * Get Number messages by school and number day.
     *
     * @invokable
     *
     * @param int $school
     *
     * @return int
     */
    public function getNbrMessage($school)
    {
        return ['d' => $this->getMapper()->getNbrMessage($school, 1), 'w' => $this->getMapper()->getNbrMessage($school, 7), 'm' => $this->getMapper()->getNbrMessage($school, 30), 'a' => $this->getMapper()->getNbrMessage($school)];
    }

    /**
     * Get Service User.
     *
     * @return \Auth\Service\User
     */
    private function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }

    /**
     * Get Service Event.
     *
     * @return \Application\Service\Event
     */
    private function getServiceEvent()
    {
        return $this->getServiceLocator()->get('app_service_event');
    }

    /**
     * Get Service Role.
     *
     * @return \Application\Service\Role
     */
    private function getServiceRole()
    {
        return $this->getServiceLocator()->get('app_service_role');
    }

    /**
     * Get Service ThreadMessage.
     *
     * @return \Application\Service\ThreadMessage
     */
    private function getServiceThreadMessage()
    {
        return $this->getServiceLocator()->get('app_service_thread_message');
    }
}
