<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class TaskShare extends AbstractService
{
    
    
    /**
     * Get all students for the instructor
     *
     * @invokable
     *
     * @param int $task
     * @param int|array $users
     *
     * @return int|array
     */
    public function add($task, $users)
    {
        $ret = array();
        $m_task_share = $this->getModel();

        foreach ($users as $u) {
            $m_task_share->setUserId($u)->setTaskId($task);
            $ret[$u] = $this->getMapper()->insert($m_task_share);
        }

        return $ret;
    }
 
}