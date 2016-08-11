<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Item
 */
namespace Application\Service;

use Dal\Service\AbstractService;
use Zend\Db\Sql\Predicate\IsNull;
use Application\Model\Item as ModelItem;
use Zend\Db\Sql\Predicate\Operator;
use Application\Model\Library as ModelLibrary;
use Application\Model\Role as ModelRole;

/**
 * Class Item.
 */
class Item extends AbstractService
{

    /**
     * Configuration of type module component.
     *
     * @var array
     */
    protected $conf = [ModelItem::TYPE_CAPSTONE_PROJECT => [ModelItem::CMP_CHAT => false],ModelItem::TYPE_CHAT => [ModelItem::CMP_CHAT => true],ModelItem::TYPE_HANGOUT => [ModelItem::CMP_VIDEOCONF => true],ModelItem::TYPE_DISCUSSION => [ModelItem::CMP_DISCUSSION => true],ModelItem::TYPE_DOCUMENT => [ModelItem::CMP_DOCUMENT => true],ModelItem::TYPE_EQCQ => [ModelItem::CMP_EQCQ => true],ModelItem::TYPE_INDIVIDUAL_ASSIGNMENT => [ModelItem::CMP_CHAT => false],ModelItem::TYPE_LIVE_CLASS => [ModelItem::CMP_VIDEOCONF => true,ModelItem::CMP_CHAT => true],ModelItem::TYPE_MODULE => [],ModelItem::TYPE_POLL => [ModelItem::CMP_POLL => true],ModelItem::TYPE_TXT => [],ModelItem::TYPE_WORKGROUP => [ModelItem::CMP_VIDEOCONF => true,ModelItem::CMP_CHAT => true,ModelItem::CMP_EQCQ => false]];

    /**
     * Add Item.
     *
     * @invokable
     *
     * @param int $course            
     * @param int $grading_policy_id            
     * @param string $title            
     * @param string $describe            
     * @param int $duration            
     * @param string $type            
     * @param array $data            
     * @param array $ct            
     * @param array $opt            
     * @param int $set_id            
     * @param bool $has_submission            
     * @param string $start            
     * @param string $end            
     * @param string $cut_off            
     * @param int $parent_id            
     * @param int $order_id            
     * @param bool $has_all_student            
     * @param bool $is_grouped            
     * @param array $submission            
     * @param bool $is_complete            
     * @param int $coefficient            
     *
     * @throws \Exception
     *
     * @return int
     */
    public function add($course, $grading_policy_id = null, $title = null, $describe = null, $duration = null, $type = null, $data = null, $ct = null, $opt = null, $set_id = null, $has_submission = null, $start = null, $end = null, $cut_off = null, $parent_id = null, $order_id = null, $has_all_student = null, $is_grouped = null, $submission = null, $is_complete = null, $coefficient = null)
    {
        if (! isset($this->conf[$type])) {
            return;
        }
        
        $m_item = $this->getModel()
            ->setCourseId($course)
            ->setGradingPolicyId($grading_policy_id)
            ->setTitle($title)
            ->setDescribe($describe)
            ->setDuration($duration)
            ->setType($type)
            ->setStart($start)
            ->setHasAllStudent($has_all_student)
            ->setIsGrouped($is_grouped)
            ->setEnd($end)
            ->setIsComplete($is_complete)
            ->setCutOff($cut_off)
            ->setSetId($set_id)
            ->setHasSubmission($has_submission)
            ->setCoefficient($coefficient)
            ->setParentId(($parent_id === 0) ? null : $parent_id);
        
        if ($this->getMapper()->insert($m_item) <= 0) {
            throw new \Exception('error insert item');
        }
        
        $item_id = $this->getMapper()->getLastInsertValue();
        $this->updateOrderId($item_id, $parent_id, $order_id);
        
        // CONTRAINTE
        if (null !== $ct) {
            if (isset($ct['date'])) {
                foreach ($ct['date'] as $date) {
                    $this->getServiceCtDate()->add($item_id, $date['date'], (isset($date['after'])) ? $date['after'] : null);
                }
            }
            if (isset($ct['done'])) {
                foreach ($ct['done'] as $done) {
                    $this->getServiceCtDone()->add($item_id, $done['target'], (isset($done['all'])) ? $done['all'] : null);
                }
            }
            if (isset($ct['group'])) {
                foreach ($ct['group'] as $group) {
                    $this->getServiceCtGroup()->add($item_id, $group['group'], (isset($group['belongs'])) ? $group['belongs'] : null);
                }
            }
            if (isset($ct['rate'])) {
                foreach ($ct['rate'] as $rate) {
                    $this->getServiceCtRate()->add($item_id, $rate['target'], (isset($rate['inf'])) ? $rate['inf'] : null, (isset($rate['sup'])) ? $rate['sup'] : null);
                }
            }
        }
        // OPTION GRADING
        if (null !== $opt) {
            if (isset($opt['grading'])) {
                $this->getServiceOptGrading()->add($item_id, (isset($opt['grading']['mode'])) ? $opt['grading']['mode'] : null, (isset($opt['grading']['has_pg'])) ? $opt['grading']['has_pg'] : null, (isset($opt['grading']['pg_nb'])) ? $opt['grading']['pg_nb'] : null, (isset($opt['grading']['pg_auto'])) ? $opt['grading']['pg_auto'] : null, (isset($opt['grading']['pg_due_date'])) ? $opt['grading']['pg_due_date'] : null, (isset($opt['grading']['pg_can_view'])) ? $opt['grading']['pg_can_view'] : null, (isset($opt['grading']['user_can_view'])) ? $opt['grading']['user_can_view'] : null, (isset($opt['grading']['pg_stars'])) ? $opt['grading']['pg_stars'] : null);
            }
        }
        
        if (null !== $submission) {
            $this->getServiceSubmission()->add($submission, $item_id);
        }
        
        // si il y a eu une mis a jour and si on a mis a jour le champ complete
        if ($is_complete == true) {
            $res_submission = $this->getServiceSubmission()->getList($item_id);
            foreach ($res_submission as $m_submission) {
                $this->getServiceEvent()->programmationNew($m_submission->getId());
            }
        }
        
        $this->initCmp($type, $data, $item_id);
        
        return $item_id;
    }

    /**
     * Initialisation Component.
     *
     * @param string $type            
     * @param array $data            
     * @param int $item_id            
     */
    private function initCmp($type, $data, $item_id)
    {
        $tconf = $this->conf[$type];
        foreach ($tconf as $component => $v) {
            if ($v === true) {
                $this->factorieComponent($component, ((isset($data[$component])) ? $data[$component] : []), $item_id);
            } elseif (isset($data[$component]) && $data[$component] !== false) {
                $this->factorieComponent($component, $data[$component], $item_id);
            }
        }
    }

    /**
     * Factory Component.
     *
     * @param string $component            
     * @param array $data            
     * @param int $item_id            
     *
     * @return mixed
     */
    private function factorieComponent($component, $data, $item_id)
    {
        $cmp = false;
        switch ($component) {
            case ModelItem::CMP_CHAT:
                break;
            case ModelItem::CMP_DISCUSSION:
                $cmp = $this->addCmpThread($data, $item_id);
                break;
            case ModelItem::CMP_DOCUMENT:
                $this->addCmpDocument($data, $item_id);
                break;
            case ModelItem::CMP_EQCQ:
                break;
            case ModelItem::CMP_POLL:
                $cmp = $this->addCmpPoll($data, $item_id);
                break;
            case ModelItem::CMP_VIDEOCONF:
                $cmp = $this->addCmpVideoconf($data, $item_id);
                break;
        }
        
        return $cmp;
    }

    /**
     * Add Poll to item.
     *
     * @param array $data            
     * @param int $item_id            
     *
     * @return int
     */
    public function addCmpPoll($data, $item_id)
    {
        if (empty($data)) {
            return;
        }
        
        $title = isset($data['title']) ? $data['title'] : null;
        $poll_item = isset($data['poll_item']) ? $data['poll_item'] : null;
        $expiration = isset($data['expiration']) ? $data['expiration'] : null;
        $time_limit = isset($data['time_limit']) ? $data['time_limit'] : null;
        $attempt_count = isset($data['attempt_count']) ? $data['attempt_count'] : null;
        
        return $this->getServicePoll()->addOrUpdate($item_id, $title, $poll_item, $expiration, $time_limit, $attempt_count);
    }

    /**
     * Add hangout to item.
     *
     * @param array $data            
     * @param int $item_id            
     *
     * @return int
     */
    public function addCmpVideoconf($data, $item_id)
    {
        $record = isset($data['record']) ? $data['record'] : null;
        $nb_user_autorecord = isset($data['nb_user_autorecord']) ? $data['nb_user_autorecord'] : null;
        $allow_intructor = isset($data['allow_intructor']) ? $data['allow_intructor'] : null;
        $has_eqcq = isset($data['has_eqcq']) ? $data['has_eqcq'] : null;
        $rules = isset($data['rules']) ? $data['rules'] : null;
        
        return $this->getServiceConversationOpt()->addOrUpdate($item_id, $record, $nb_user_autorecord, $allow_intructor, $has_eqcq, $rules);
    }

    /**
     * Add Thread to Item.
     *
     * @param array $data            
     * @param int $item_id            
     *
     * @return int
     */
    public function addCmpThread($data, $item_id)
    {
        if (empty($data)) {
            return;
        }
        if ($thread_id = isset($data['thread_id']) ? $data['thread_id'] : null) {
            return $this->getServiceThread()->update($thread_id, null, $item_id);
        } else {
            $course = isset($data['course']) ? $data['course'] : null;
            $describe = isset($data['describe']) ? $data['describe'] : null;
            $title = isset($data['title']) ? $data['title'] : null;
            
            return $this->getServiceThread()->add($title, $course, $describe, $item_id);
        }
    }

    /**
     * Add Document to Item.
     *
     * @param array $data            
     * @param int $item_id            
     *
     * @return int
     */
    public function addCmpDocument($data, $item_id)
    {
        if (empty($data)) {
            return;
        }
        
        $name = isset($data['name']) ? $data['name'] : null;
        $type = isset($data['type']) ? $data['type'] : null;
        $link = isset($data['link']) ? $data['link'] : null;
        $token = isset($data['token']) ? $data['token'] : null;
        
        return $this->getServiceDocument()->add($name, $type, $link, $token, $item_id, null, ModelLibrary::FOLDER_OTHER_INT);
    }

    /**
     * Get List User.
     *
     * @invokable
     *
     * @param int $item_id            
     * @param int $user_id            
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListUsers($item_id, $user_id = null)
    {
        return $this->getServiceUser()->getListUsersGroupByItemAndUser($item_id, $user_id);
    }

    /**
     * Get List Submissions.
     *
     * @invokable
     *
     * @param array $filter            
     * @param string $type            
     * @param array $program            
     * @param array $course            
     * @param string $due            
     * @param bool $notgraded            
     * @param string $search            
     *
     * @return array
     */
    public function getListSubmissions($filter = null, $type = null, $program = null, $course = null, $due = null, $notgraded = null, $search = null)
    {
        $mapper = $this->getMapper()->usePaginator($filter);
        $identity = $this->getServiceUser()->getIdentity();
        $is_academic = (in_array(ModelRole::ROLE_ACADEMIC_STR, $identity['roles']));

        $res_item = $mapper->getListSubmissions($identity['id'], $type, $program, $course, $due, $notgraded, $search, $is_academic);
        
        return ['list' => $res_item,'count' => $mapper->count()];
    }

    /**
     * Update Item.
     *
     * @invokable
     *
     * @param int $id            
     * @param int $grading_policy_id            
     * @param string $title            
     * @param string $describe            
     * @param int $duration            
     * @param string $type            
     * @param string $data            
     * @param int $set_id            
     * @param bool $has_submission            
     * @param string $start            
     * @param string $end            
     * @param string $cut_off            
     * @param int $parent_id            
     * @param int $order_id            
     * @param bool $has_all_student            
     * @param bool $is_grouped            
     * @param array $submission            
     * @param bool $is_complete            
     * @param int $coefficient            
     * @param array $opt            
     *
     * @return int
     */
    public function update($id, $grading_policy_id = null, $title = null, $describe = null, $duration = null, $type = null, $data = null, $set_id = null, $has_submission = null, $start = null, $end = null, $cut_off = null, $parent_id = null, $order_id = null, $has_all_student = null, $is_grouped = null, $submission = null, $is_complete = null, $coefficient = null, $opt = null)
    {
        $m_item = $this->getModel()
            ->setId($id)
            ->setGradingPolicyId(($grading_policy_id === 0) ? new IsNull() : $grading_policy_id)
            ->setTitle($title)
            ->setDescribe($describe)
            ->setDuration($duration)
            ->setStart($start)
            ->setSetId(0 !== $set_id ? $set_id : new IsNull())
            ->setEnd($end)
            ->setIsComplete($is_complete)
            ->setCutOff($cut_off)
            ->setType($type)
            ->setHasAllStudent($has_all_student)
            ->setIsGrouped($is_grouped)
            ->setHasSubmission($has_submission)
            ->setCoefficient($coefficient)
            ->setParentId(($parent_id === 0) ? new IsNull() : $parent_id)
            ->setUpdatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
        
        if ($order_id !== null || $parent_id !== null) {
            $this->updateOrderId($id, $parent_id, $order_id);
        }
        
        if (null !== $data) {
            if (null === $type) {
                $type = $this->get($id)->getType();
            }
            $this->initCmp($type, $data, $id);
        }
        
        // OPTION GRADING
        if (null !== $opt) {
            if (isset($opt['grading'])) {
                $this->getServiceOptGrading()->add($id, (isset($opt['grading']['mode'])) ? $opt['grading']['mode'] : null, (isset($opt['grading']['has_pg'])) ? $opt['grading']['has_pg'] : null, (isset($opt['grading']['pg_nb'])) ? $opt['grading']['pg_nb'] : null, (isset($opt['grading']['pg_auto'])) ? $opt['grading']['pg_auto'] : null, (isset($opt['grading']['pg_due_date'])) ? $opt['grading']['pg_due_date'] : null, (isset($opt['grading']['pg_can_view'])) ? $opt['grading']['pg_can_view'] : null, (isset($opt['grading']['user_can_view'])) ? $opt['grading']['user_can_view'] : null, (isset($opt['grading']['pg_stars'])) ? $opt['grading']['pg_stars'] : null);
            }
        }
        
        if (null !== $submission) {
            $this->getServiceSubmission()->add($submission, $id);
        }
        
        $actual_is_complete = null;
        $actual_start = null;
        if ($is_complete == true || $start !== true) {
            $actual_item = $this->getMapper()
                ->select($this->getModel()
                ->setId($id))
                ->current();
            if ($is_complete == true) {
                $actual_is_complete = $actual_item->getIsComplete();
            }
            if ($start !== true) {
                $actual_start = $actual_item->getStart();
            }
        }
        
        $ret = $this->getMapper()->update($m_item);
        // si il y a eu une mis a jour and si on a mis a jour le champ complete
        if ($ret === 1 && $is_complete == true && $actual_is_complete == false) {
            $res_submission = $this->getServiceSubmission()->getList($id);
            foreach ($res_submission as $m_submission) {
                $this->getServiceEvent()->programmationNew($m_submission->getId());
            }
        }
        
        if ($ret === 1 && $start !== null && $is_complete == true && $actual_is_complete == true && $actual_start !== $start) {
            $res_submission = $this->getServiceSubmission()->getList($id);
            foreach ($res_submission as $m_submission) {
                $this->getServiceEvent()->programmationUpdated($m_submission->getId());
            }
        }
        
        return $ret;
    }

    /**
     * Get List Item By Course.
     *
     * @param int $course_id            
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListByCourse($course_id)
    {
        return $this->getMapper()->select($this->getModel()
            ->setCourseId($course_id));
    }

    /**
     * Get List.
     *
     * @invokable
     *
     * @param int $course            
     * @param int $parent_id            
     * @param int $start            
     * @param int $end            
     * @param array $type
     *            @retun array
     */
    public function getList($course = null, $parent_id = null, $start = null, $end = null, $type = null)
    {
        if (null === $course && $start === null && $end === null) {
            throw new \Exception('error course is not declarer');
        }
        
        $identity = $this->getServiceUser()->getIdentity();
        $user_id = $identity['id'];
        
        $is_student = false;
        if (array_key_exists(ModelRole::ROLE_STUDENT_ID, $identity['roles'])) {
            $is_student = true;
        }
        
        // @todo Faire du propre dans les roles une fois que les relations seront ok
        $is_admin_academic = (in_array(ModelRole::ROLE_SADMIN_STR, $identity['roles'])) || (in_array(ModelRole::ROLE_ADMIN_STR, $identity['roles'])) || (in_array(ModelRole::ROLE_ACADEMIC_STR, $identity['roles']));
        
        
        $res_item = $this->getMapper()->getList($user_id, $course, $parent_id, $start, $end, $type, $is_admin_academic);
        $ar_item = (null !== $start || null !== $end) ? $res_item->toArray() : $res_item->toArrayParent('order_id');
        
        foreach ($ar_item as $k => &$item) {
            $item['done'] = $this->getServiceCtDone()
                ->get($item['id'])
                ->toArray();
            $item['rate'] = $this->getServiceCtRate()
                ->get($item['id'])
                ->toArray();
            if ($is_student === true) {
                if ($item['is_complete'] === 0 || ($item['type'] !== ModelItem::TYPE_TXT && $item['type'] !== ModelItem::TYPE_DOCUMENT && $item['type'] !== ModelItem::TYPE_MODULE && $this->checkAllow($item['id'], $user_id) === false)) {
                    unset($ar_item[$k]);
                }
                $item['checked'] = $this->checkVisibility($item, $user_id);
            }
        }
        
        return array_values($ar_item);
    }

    /**
     * Get List.
     *
     * @invokable
     *
     * @param int $course            
     * @param int $parent_id            
     * @param int $start            
     * @param int $end            
     * @param array $type
     *            @retun array
     */
    public function getListTmp($course = null, $parent_id = null, $start = null, $end = null, $type = null)
    {
        if (null === $course && $start === null && $end === null) {
            throw new \Exception('error course is not declarer');
        }
        
        $ar_user = $this->getServiceUser()->getIdentity();
        $user_id = $ar_user['id'];
        
        $is_student = false;
        if (array_key_exists(ModelRole::ROLE_STUDENT_ID, $ar_user['roles'])) {
            $is_student = true;
        }
        
        // @todo Faire du propre dans les roles une fois que les relations seront ok
        $is_admin_academic = (in_array(ModelRole::ROLE_SADMIN_STR, $identity['roles'])) || (in_array(ModelRole::ROLE_ADMIN_STR, $identity['roles'])) || (in_array(ModelRole::ROLE_ACADEMIC_STR, $identity['roles']));
        
        
        $res_item = $this->getMapper()->getListTmp($user_id, $course, $parent_id, $start, $end, $type, $is_admin_academic);
        $ar_item = $res_item->toArray();
        
        return array_values($ar_item);
    }

    /**
     * Check if user is allowed.
     *
     * @param int $item_id            
     * @param int $user_id            
     *
     * @return bool
     */
    public function checkAllow($item_id, $user_id = null)
    {
        if (null === $user_id) {
            $user_id = $this->getServiceUser()->getIdentity()['id'];
        }
        
        return $this->getServiceUser()->doBelongs($item_id, $user_id);
    }

    /**
     * Check visibility to item by contrainte.
     *
     * @param array $item            
     * @param int $user_id            
     *
     * @return bool
     */
    public function checkVisibility($item, $user_id = null)
    {
        if (null === $user_id) {
            $user_id = $this->getServiceUser()->getIdentity()['id'];
        }
        
        $done = 1;
        $rate = 2;
        $date = 0; // 3;
        if (isset($item['done']) && count($item['done']) > 0) {
            foreach ($item['done'] as $i) {
                $m_submission = $this->getServiceSubmission()->getSubmissionUser($i['target_id'], $user_id);
                if (null !== $m_submission) {
                    if ($i['all'] == 1) {
                        if ($m_submission->getSubmitDate() === null || $m_submission->getSubmitDate() instanceof IsNull) {
                            $done = 0;
                            break;
                        }
                    } else {
                        if ($m_submission->getSubmissionUser()->getSubmitDate() === null || $m_submission->getSubmissionUser()->getSubmitDate() instanceof IsNull) {
                            $done = 0;
                            break;
                        }
                    }
                } else {
                    $done = 0;
                    break;
                }
            }
        }
        
        if (isset($item['rate']) && count($item['rate']) > 0) {
            foreach ($item['rate'] as $i) {
                $m_submission = $this->getServiceSubmission()->getSubmissionUser($i['target_id'], $user_id);
                if (null !== $m_submission) {
                    $grade = $m_submission->getSubmissionUser()->getGrade();
                    if (is_numeric($grade)) {
                        if (is_numeric($i['inf']) && $i['inf'] < $grade) {
                            $rate = 0;
                            break;
                        }
                        if (is_numeric($i['sup']) && $i['sup'] > $grade) {
                            $rate = 0;
                            break;
                        }
                    } else {
                        $rate = 0;
                    }
                } else {
                    $rate = 0;
                    break;
                }
            }
        }
        
        return $done | $rate | $date;
    }

    /**
     * Get List Item For Calendar.
     *
     * @invokable
     *
     * @param string $start            
     * @param string $end            
     *
     * @return array
     */
    public function getListForCalendar($start = null, $end = null)
    {
        return $this->getMapper()->getListForCalendar($this->getServiceUser()
            ->getIdentity(), $start, $end);
    }

    /**
     * Get List Item by user.
     *
     * @invokable
     *
     * @param int $user            
     *
     * @return array
     */
    public function getListByUser($user)
    {
        return $this->getMapper()
            ->select($this->getModel()
            ->setCourseId($course))
            ->toArray();
    }

    /**
     * Get Item Criterias.
     *
     * @invokable
     *
     * @param int $id            
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getCriterias($id)
    {
        return $this->getServiceCriteria()->getListByItem($id);
    }

    /**
     * Get Item by Type.
     *
     * @invokable
     *
     * @param int $course            
     * @param int $type            
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getItemByType($course, $type)
    {
        $m_item = $this->getModel()
            ->setType($type)
            ->setCourse($course);
        
        return $this->getMapper()->select($m_item);
    }

    /**
     * Delete Item.
     *
     * @invokable
     *
     * @param int $id            
     *
     * @return bool
     */
    public function delete($id)
    {
        if (! is_array($id)) {
            $id = [$id];
        }
        
        foreach ($id as $i) {
            $this->sort($i);
            try {
                if ($this->getMapper()->delete($this->getModel()
                    ->setId($i)) === 0) {
                    $this->cancelSort($i);
                    
                    return false;
                }
                ;
            } catch (\Exception $e) {
                syslog(1, 'Error on item deletion : ' . $e->getMessage());
                $this->cancelSort($i);
                
                return false;
            }
        }
        
        return true;
    }

    /**
     * Get List Grade Item.
     *
     * @invokable
     *
     * @param int $grading_policy_id            
     * @param int $course            
     * @param int $user            
     * @param int $submission            
     */
    public function getListGradeItem($grading_policy_id = null, $course = null, $user = null, $submission = null)
    {
        return $this->getMapper()->getListGradeItem($grading_policy_id, $course, $user, $submission);
    }

    /**
     * Get item by submission.
     *
     * @param int $submission_id            
     *
     * @return null|\Application\Model\Item
     */
    public function getBySubmission($submission_id)
    {
        return $this->getMapper()
            ->getBySubmission($submission_id)
            ->current();
    }

    /**
     * Get Item.
     *
     * @invokable
     *
     * @param int $id            
     *
     * @throws \Exception
     *
     * @return \Application\Model\Item
     */
    public function get($id)
    {
        $ar_user = $this->getServiceUser()->getIdentity();
        $roles = $ar_user['roles'];
        
        $is_allow = true;
        $res_item = ($is_allow) ? $this->getMapper()->getAllow($id) : $this->getMapper()->get($id);
        if ($res_item->count() <= 0) {
            throw new \Exception('error select item');
        }
        
        $m_item = $res_item->current();
        // si il a le role d'étudiant
        if (array_key_exists(ModelRole::ROLE_STUDENT_ID, $roles)) {
            // si il n'est pas autoriser sauf pour les txt document et module on léve une exception
            if ($m_item->getIsComplete() === 0 || ($m_item->getType() !== ModelItem::TYPE_TXT && $m_item->getType() !== ModelItem::TYPE_DOCUMENT && $m_item->getType() !== ModelItem::TYPE_MODULE && $this->checkAllow($id) === false)) {
                throw new \Exception('no autorisation for this item: ' . $id);
            }
        }
        
        $m_item->setCtDate($this->getServiceCtDate()
            ->get($m_item->getId()))
            ->setCtDone($this->getServiceCtDone()
            ->get($m_item->getId()))
            ->setCtRate($this->getServiceCtRate()
            ->get($m_item->getId()))
            ->setCtGroup($this->getServiceCtGroup()
            ->get($m_item->getId()))
            ->setVideoconf($this->getServiceConversationOpt()
            ->getByItem($m_item->getId()))
            ->setThread($this->getServiceThread()
            ->getByItem($m_item->getId()))
            ->setPoll($this->getServicePoll()
            ->getByItem($m_item->getId()));
        
        if ($m_item->getType() === ModelItem::TYPE_DOCUMENT) {
            $m_item->setDocument($this->getServiceLibrary()
                ->getListByItem($m_item->getId()));
        } else {
            $m_item->setDocument($this->getServiceLibrary()
                ->getListByParentItem($m_item->getId()));
        }
        
        return $m_item;
    }

    /**
     * Update Order Id of item.
     *
     * @param int $item            
     * @param int $parent_target            
     * @param int $order_id            
     */
    public function updateOrderId($item, $parent_target = null, $order_id = null)
    {
        $me_item = $this->getMapper()
            ->select($this->getModel()
            ->setId($item))
            ->current();
        
        $parent_id = ($me_item->getParentId() == null || $me_item->getParentId() instanceof IsNull) ? new IsNull('parent_id') : ['parent_id' => $me_item->getParentId()];
        $sort = ['order_id' => $item,'course_id' => $me_item->getCourseId()];
        $rentre = [new Operator('id', Operator::OP_NE, $item),'course_id' => $me_item->getCourseId()];
        $sortp = $rentrep = [];
        
        $parent_target = ($parent_target === null) ? $parent_id : (($parent_target === 0) ? new IsNull('parent_id') : ['parent_id' => $parent_target]);
        $order = ($order_id === null || $order_id === 0) ? new IsNull('order_id') : ['order_id' => $order_id];
        
        if (is_array($parent_id)) {
            $sort = array_merge($sort, $parent_id);
        } else {
            $sortp[] = $parent_id;
        }
        if (is_array($parent_target)) {
            $rentre = array_merge($rentre, $parent_target);
        } else {
            $rentrep[] = $parent_target;
        }
        if (is_array($order)) {
            $rentre = array_merge($rentre, $order);
        } else {
            $rentrep[] = $order;
        }
        
        $sort = array_merge($sortp, $sort);
        $rentre = array_merge($rentrep, $rentre);
        
        // JE SORT
        $this->getMapper()->update($this->getModel()
            ->setOrderId($me_item->getOrderId() === null ? new IsNull() : $me_item->getOrderId()), $sort);
        
        // JE RENTRE
        $this->getMapper()->update($this->getModel()
            ->setOrderId($item), $rentre);
        $this->getMapper()->update($this->getModel()
            ->setId($item)
            ->setOrderId(($order_id === null || $order_id === 0) ? new IsNull() : $order_id));
    }

    /**
     * Sort item.
     *
     * @param int $item_id            
     *
     * @return int
     */
    public function sort($item_id)
    {
        $me_item = $this->getMapper()
            ->select($this->getModel()
            ->setId($item_id))
            ->current();
        
        return $this->getMapper()->update($this->getModel()
            ->setOrderId($me_item->getOrderId() === null ? new IsNull() : $me_item->getOrderId()), ['order_id' => $me_item->getId(),'course_id' => $me_item->getCourseId()]);
    }

    /**
     * Cancel a sort item.
     *
     * @param intem $item            
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function cancelSort($item_id)
    {
        $me_item = $this->getMapper()
            ->select($this->getModel()
            ->setId($item_id))
            ->current();
        
        return $this->getMapper()->cancelSort($me_item->getId(), $me_item->getOrderId());
    }

    /**
     * Get Service ItemMaterialDocumentRelation.
     *
     * @return \Application\Service\ItemMaterialDocumentRelation
     */
    private function getServiceItemMaterialDocumentRelation()
    {
        return $this->getServiceLocator()->get('app_service_item_material_document_relation');
    }

    /**
     * Get Service Submission.
     *
     * @return \Application\Service\Submission
     */
    private function getServiceSubmission()
    {
        return $this->getServiceLocator()->get('app_service_submission');
    }

    /**
     * Get Service GradingPolicy.
     *
     * @return \Application\Service\GradingPolicy
     */
    private function getServiceGradingPolicy()
    {
        return $this->getServiceLocator()->get('app_service_grading_policy');
    }

    /**
     * Get Service User.
     *
     * @return \Application\Service\User
     */
    private function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }

    /**
     * Get Service Library.
     *
     * @return \Application\Service\Library
     */
    private function getServiceLibrary()
    {
        return $this->getServiceLocator()->get('app_service_library');
    }

    /**
     * Get Service Document.
     *
     * @return \Application\Service\Document
     */
    private function getServiceDocument()
    {
        return $this->getServiceLocator()->get('app_service_document');
    }

    /**
     * Get Service Poll.
     *
     * @return \Application\Service\Poll
     */
    private function getServicePoll()
    {
        return $this->getServiceLocator()->get('app_service_poll');
    }

    /**
     * Get Service CtDate.
     *
     * @return \Application\Service\CtDate
     */
    private function getServiceCtDate()
    {
        return $this->getServiceLocator()->get('app_service_ct_date');
    }

    /**
     * Get Service CtDone.
     *
     * @return \Application\Service\CtDone
     */
    private function getServiceCtDone()
    {
        return $this->getServiceLocator()->get('app_service_ct_done');
    }

    /**
     * Get Service CtGroup.
     *
     * @return \Application\Service\CtGroup
     */
    private function getServiceCtGroup()
    {
        return $this->getServiceLocator()->get('app_service_ct_group');
    }

    /**
     * Get Service CtRate.
     *
     * @return \Application\Service\CtRate
     */
    private function getServiceCtRate()
    {
        return $this->getServiceLocator()->get('app_service_ct_rate');
    }

    /**
     * Get Service OptGrading.
     *
     * @return \Application\Service\OptGrading
     */
    private function getServiceOptGrading()
    {
        return $this->getServiceLocator()->get('app_service_opt_grading');
    }

    /**
     * Get Service Conversation.
     *
     * @return \Application\Service\Conversation
     */
    private function getServiceConversation()
    {
        return $this->getServiceLocator()->get('app_service_conversation');
    }

    /**
     * Get Service Thread.
     *
     * @return \Application\Service\Thread
     */
    private function getServiceThread()
    {
        return $this->getServiceLocator()->get('app_service_thread');
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
     * Get Service ConversationOpt.
     *
     * @return \Application\Service\ConversationOpt
     */
    private function getServiceConversationOpt()
    {
        return $this->getServiceLocator()->get('app_service_conversation_opt');
    }

    /**
     * Get Service Criteria.
     *
     * @return \Application\Service\Criteria
     */
    private function getServiceCriteria()
    {
        return $this->getServiceLocator()->get('app_service_criteria');
    }
}
