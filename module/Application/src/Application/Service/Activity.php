<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Activity
 */
namespace Application\Service; 

use Dal\Service\AbstractService;
use JRpc\Json\Server\Exception\JrpcException;
use Zend\Db\Sql\Predicate\Between;
use Application\Model\Role as ModelRole;

/**
 * Class Activity
 */
class Activity extends AbstractService
{
    /**
     * Create Activity
     *
     * @invokable
     *
     * @param  array $activities
     * @return array
     */
    public function add($activities)
    {
        $ret = [];
        $user = $this->getServiceUser()->getIdentity()['id'];
        foreach ($activities as $activity) {
            $date = (isset($activity['date'])) ? $activity['date'] : null;
            $event = (isset($activity['event'])) ? $activity['event'] : null;
            $object = (isset($activity['object'])) ? $activity['object'] : null;
            $target = (isset($activity['target'])) ? $activity['target'] : null;

            $ret[] = $this->_add($date, $event, $object, $target, $user);
        }
        $this->getServiceConnection()->add();

        return $ret;
    }

    /**
     * Create Activity.
     *
     * @param string $date
     * @param string $event
     * @param array  $object
     * @param array  $target
     * @param int    $user_id
     *
     * @throws \Exception
     *
     * @return int
     */
    public function _add($date = null, $event = null, $object = null, $target = null, $user_id = null)
    {
        $m_activity = $this->getModel();
        $m_activity->setEvent($event);
        $m_activity->setDate($date);
        $m_activity->setUserId($user_id);

        if (null !== $object) {
            if (isset($object['id'])) {
                $m_activity->setObjectId($object['id']);
            }
            if (isset($object['value'])) {
                $m_activity->setObjectValue($object['value']);
            }
            if (isset($object['name'])) {
                $m_activity->setObjectName($object['name']);
            }
            if (isset($object['data'])) {
                $m_activity->setObjectData(json_encode($object['data']));
            }
        }
        if (null !== $target) {
            if (isset($target['id'])) {
                $m_activity->setTargetId($target['id']);
            }
            if (isset($target['name'])) {
                $m_activity->setTargetName($target['name']);
            }
            if (isset($target['data'])) {
                $m_activity->setTargetData(json_encode($target['data']));
            }
        }

        if ($this->getMapper()->insert($m_activity) <= 0) {
            throw new \Exception('error insert ativity');
        }

        return $this->getMapper()->getLastInsertValue();
    }

    /**
    * Get List activity.
    *
    * @invokable
    *
    * @param string $event
    * @param int     $object_id
    * @param string $object_name
    * @param array  $school_id
    * @param array  $program_id
    * @param array  $course_id
    * @param array  $item_id
    * @param array  $user_id
    *
    * @return array
    */
    public function getListWithFilters($event = null, $object_id = null, $object_name = null, $school_id = null, $program_id = null, $course_id = null, $item_id = null, $user_id = null)
    {
        $identity = $this->getServiceUser()->getIdentity();
        $is_academic = in_array(ModelRole::ROLE_ACADEMIC_STR, $identity['roles']);

        if ($is_academic) {
            if (null !== $school_id) {
                if (!is_array($school_id)) {
                    $school_id = [$school_id];
                }
                foreach ($school_id as $school) {
                    if (!$this->getServiceUser()->checkOrg($school)) {
                        throw new JrpcException('unauthorized orgzanization: ' . $school);
                    }
                }
            } else {
                $school_id = [];
                foreach ($identity['organizations'] as $school) {
                    $school_id[] = $school['id'];
                }
            }
        }

        $res_activity = $this->getMapper()->getListWithFilters($identity['id'], $event, $object_id, $object_name, $school_id, $program_id, $course_id, $item_id, $user_id, $is_academic);
        foreach ($res_activity as $m_activity) {
            $m_activity->setDate((new \DateTime($m_activity->getDate()))->format('Y-m-d\TH:i:s\Z'));
            $o_data = $m_activity->getObjectData();
            if (is_string($o_data)) {
                $m_activity->setObjectData(json_decode($o_data, true));
            }
            $o_target = $m_activity->getTargetData();
            if (is_string($o_target)) {
                $m_activity->setTargetData(json_decode($o_target, true));
            }
        }
         return $res_activity;
    }


    /**
     * Get List Activity.
     *
     * @invokable
     *
     * @param int     $user
     * @param array   $filter
     * @param string  $search
     * @param string  $start_date
     * @param string  $end_date
     *
     * @return array
     */
    public function getList($filter = [], $search = null, $start_date = null, $end_date = null, $user = null)
    {
        $mapper = $this->getMapper();
        $res_activity = $mapper->usePaginator($filter)->getList($search, $start_date, $end_date, $user);

        return ['count' => $mapper->count(), 'list' => $res_activity];
    }
    
    
    
    /**
     * Get List connections.
     *
     * @invokable
     *
     * @param string  $start_date
     * @param string  $end_date
     * @param string  $interval_date
     * @param int     $organization_id
     *
     * @return array
     */
    public function getConnectionCount( $start_date = null, $end_date = null, $interval_date = 'D', $organization_id  = null){
        
        $interval = $this->interval($interval_date);
        $identity = $this->getServiceUser()->getIdentity();
        
        return $this->getMapper()->getConnectionCount($identity['id'],$interval, $start_date, $end_date, $organization_id);
    }

    /**
     * Get List connections.
     *
     * @invokable
     *
     * @param int     $organization_id
     * @param int     $user
     * @param int     $user_id
     * @param string  $start_date
     * @param string  $interval_date
     * @param string  $end_date
     *
     * @return array
     */
    public function getConnections($start_date = null, $end_date = null, $user = null, $organization_id = null, $interval_date = 'D', $user_id = null)
    {
        $mapper = $this->getMapper();
        $res_activity = $mapper->getList(null, $start_date, $end_date, $user, $organization_id, $user_id);
        $arrayUser = [];
        $connections = [];
        $interval = $this->interval($interval_date);
        foreach ($res_activity as $m_activity)
        {
            if(!array_key_exists($m_activity->getUserId(), $arrayUser))
            {
                $arrayUser[$m_activity->getUserId()] = 
                    ['start_date' => $m_activity->getDate(), 'end_date' => $m_activity->getDate()];
            }
            else 
            {
                $difference = (strtotime($m_activity->getDate()) - strtotime($arrayUser[$m_activity->getUserId()]['end_date']));
                if ($difference < 3600 && strcmp(substr($m_activity->getDate(), 0, $interval), substr($arrayUser[$m_activity->getUserId()]['end_date'], 0, $interval)) == 0)
                  {
                    $arrayUser[$m_activity->getUserId()]['end_date'] = $m_activity->getDate();
                  }
                else
                  {
                    $actual_day = substr($arrayUser[$m_activity->getUserId()]['end_date'], 0, $interval);
                    if (!array_key_exists($actual_day, $connections))
                     {
                        $connections[$actual_day] = [];
                     }
                    $connections[$actual_day][] = strtotime($arrayUser[$m_activity->getUserId()]['end_date']) - strtotime($arrayUser[$m_activity->getUserId()]['start_date']);

                    $arrayUser[$m_activity->getUserId()] = 
                        ['start_date' => $m_activity->getDate(), 'end_date' => $m_activity->getDate()];
                  }
            }

        }

        foreach ($arrayUser as $m_arrayUser)
        {
          $actual_day = substr($arrayUser[$m_activity->getUserId()]['end_date'], 0, $interval);
          if (!array_key_exists($actual_day, $connections))
            {
              $connections[$actual_day] = [];
            }
          $connections[$actual_day][] = strtotime($m_arrayUser['end_date']) - strtotime($m_arrayUser['start_date']);
        }

        foreach ($connections as $actual_day => $m_connections)
        {
            $connections[$actual_day] = array_sum($m_connections) / count($m_connections);
        }

        return $connections;
    }

    public function interval($interval = 'D') 
    {
        $ret = false;
        switch ($interval) {
            case 'D':
                $ret = 10;
                break;
            case 'M':
                $ret = 7;
                break;
            case 'Y':
                $ret = 4;
                break;
        }

        return $ret;
    }

    /**
     * @invokable
     * 
     * @param int $id
     */
     public function get($id)
     {
         return $this->getMapper()->get($id);
     }
     
    /**
     * Get List Activity With User Model.
     *
     * @invokable
     *
     * @param array  $filter
     * @param string $search
     * @param string $date
     *
     * @return array
     */
    public function getListWithUser($filter = null, $search = null, $date = null)
    {
        $mapper = $this->getMapper();
        $res_activity = $mapper->usePaginator($filter)->getListWithUser($search);

        return ['count' => $mapper->count(), 'list' => $res_activity];
    }

    /**
     * Aggregate Activity.
     *
     * @invokable
     *
     * @param array|string $event
     * @param int          $user
     * @param int          $object_id
     * @param string       $object_name
     * @param int          $target_id
     * @param string       $target_name
     *
     * @return array
     */
    public function aggregate($event, $user, $object_id = null, $object_name = null, $target_id = null, $target_name = null)
    {
        $ret = [];
        if (!is_array($event)) {
            $event = array($event);
        }

        foreach ($event as $e) {
            $ret[$e] = $this->getMapper()
                ->aggregate($e, $user, $object_id, $object_name, $target_id, $target_name)
                ->current();
        }
        return $ret;
    }

    /**
     * Get Service User.
     *
     * @return \Application\Service\User
     */
    private function getServiceUser()
    {
        return $this->container->get('app_service_user');
    }

    /**
     * Get Service Connection.
     *
     * @return \Application\Service\Connection
     */
    private function getServiceConnection()
    {
        return $this->container->get('app_service_connection');
    }
}
