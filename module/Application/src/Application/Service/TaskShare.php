<?php
/**
 * 
 * TheStudnet (http://thestudnet.com)
 *
 * Task Share
 *
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class TaskShare
 */
class TaskShare extends AbstractService
{

    /**
     * Get all students for the instructor.
     *
     * @invokable
     *
     * @param int $task            
     * @param int|array $users            
     * @return array
     */
    public function add($task, $users)
    {
        $ret = [];
        $m_task_share = $this->getModel()->setTaskId($task);
        
        $uok = [];
        foreach ($users as $u) {
            $m_task_share->setUserId($u);
            if ($ret[$u] = $this->getMapper()->insert($m_task_share)) {
                $uok[] = $u;
            }
        }
        
        if(!empty($uok)) {
            $this->getServiceEvent()->taskshared($task, $uok);
        }
        
        return $ret;
    }

    /**
     * Get Service Event
     *
     * @return \Application\Service\Event
     */
    private function getServiceEvent()
    {
        return $this->getServiceLocator()->get('app_service_event');
    }
}
