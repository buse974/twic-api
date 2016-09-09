<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Activity
 */
namespace Application\Service;

use Dal\Service\AbstractService;
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
     * @param array $activities
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
     * @param id $object_id
     * @param string $object_name
     * @param array $school_id
     * @param array $program_id
     * @param array $course_id
     * @param array $item_id
     * @param array $user_id
     *
     * @return array
     */
    public function getListWithFilters($event = null, $object_id = null, $object_name = null, $school_id = null, $program_id = null, $course_id = null, $item_id = null, $user_id = null)
    {
        $identity = $this->getServiceUser()->getIdentity();
        $is_academic = in_array(ModelRole::ROLE_ACADEMIC_STR, $identity['roles']);
     
        if($is_academic){
            if(null !== $school_id){
                if(!is_array($school_id)){
                    $school_id = [$school_id];
                }
                foreach($school_id as $school){
                    if (!$this->getServiceUser()->checkOrg($school)) {
                        throw new JrpcException('unauthorized orgzanization: ' . $school);
                    }
                }
            }
            else{
                $school_id = [];
                foreach($identity['organizations'] as $school){
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
     * Get List activity.
     * 
     * @invokable
     *
     * @param string $date
     * @param string $event
     * @param array  $object
     * @param array  $target
     * @param array  $user
     * @param string $start_date
     * @param string $end_date
     * @param array  $filter
     *
     * @return array
     */
    public function getList($date = null, $event = null, $object = null, $target = null, $user = null, $start_date = null, $end_date = null, $filter = null)
    {
        
        
        $m_activity = $this->getModel();
        $m_activity->setEvent($event)
            ->setDate($date)
            ->setUserId($user);

        if (null !== $start_date && null !== $end_date) {
            $m_activity->setDate(new Between('date', $start_date, $end_date));
        }
        if (null !== $object) {
            if (isset($object['id'])) {
                $m_activity->setObjectId($object['id']);
            }
            if (isset($object['name'])) {
                $m_activity->setObjectName($object['name']);
            }
            if (isset($object['data'])) {
                $m_activity->setObjectData($object['data']);
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
                $m_activity->setTargetData($target['data']);
            }
        }

        $mapper = ($filter !== null) ? $this->getMapper()->usePaginator($filter) : $this->getMapper();

        $res_activity = $mapper->select($m_activity, array('date' => 'ASC'));
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

        return ($filter !== null) ? ['count' => $mapper->count(), 'list' => $res_activity] : $res_activity;
    }

    /**
     * Get List Activity With User Model.
     * 
     * @invokable
     *
     * @param array  $filter
     * @param string $search
     *
     * @return array
     */
    public function getListWithUser($filter = null, $search = null)
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
