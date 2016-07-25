<?php
/**
 * 
 * TheStudnet (http://thestudnet.com)
 *
 * Submission Paire grader
 *
 */

namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class SubmissionPg
 */
class SubmissionPg extends AbstractService
{
    /**
     * Add Submission Speed Grader
     * 
     * @param int $submission_id
     * @param int $user_id
     * @return number
     */
    public function add($submission_id, $user_id)
    {
        $date = (new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s');

        return  $this->getMapper()->insert($this->getModel()->setUserId($user_id)->setSubmissionId($submission_id)->setDate($date));
    }

    /**
     * Delete Submission Speed Grader
     * 
     * @param int $submission_id
     * @param int $user_id
     * @return int
     */
    public function delete($submission_id, $user_id)
    {
        return  $this->getMapper()->delete($this->getModel()->setUserId($user_id)->setSubmissionId($submission_id));
    }

    /**
     * Get List Submission Speed Grader
     * 
     * @invokable
     * 
     * @param int $item_id
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListByItem($item_id)
    {
        return  $this->getMapper()->getListByItem($item_id);
    }

    /**
     * Get List
     * 
     * @param int $submission_id
     * @param int $user_id
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListBySubmission($submission_id, $user_id = null)
    {
        $m_submission_pg = $this->getModel()->setSubmissionId($submission_id);
        
        if(null !== $user_id) {
            $m_submission_pg->setUserId($user_id);
        }
        
        return  $this->getMapper()->select($m_submission_pg);
    }
    
    /**
     * Delete By Item
     * 
     * @param int $item_id
     * @return boolean
     */
    public function deleteByItem($item_id)
    {
        $res_submission = $this->getServiceSubmission()->getList($item_id);

        foreach ($res_submission as $m_submission) {
            $this->getMapper()->delete($this->getModel()->setSubmissionId($m_submission->getId()));
        }

        return true;
    }

    /**
     * Check Grader
     * 
     * @param int $submission_id
     * @param int $user_id
     * @return int
     */
    public function checkGraded($submission_id, $user_id)
    {
        return  $this->getMapper()->checkGraded($submission_id, $user_id);
    }

    /**
     * Replace submission peer grader
     * 
     * @param int $submission_id
     * @param int $user_id
     * @return int
     */
    public function replace($submission_id, $user_id)
    {
        $this->getMapper()->deleteNotIn($submission_id, $user_id);
        foreach ($user_id as $u) {
            $this->add($submission_id, $u);
        }

        return 1;
    }

    /**
     * Auto Assign peer 
     * 
     * @invokable
     * 
     * @param int $item_id
     */
    public function autoAssign($item_id)
    {
        $m_opt_grading = $this->getServiceOptGrading()->get($item_id);
        if ($m_opt_grading === null || $m_opt_grading === false || $m_opt_grading->getHasPg() == 0 || $m_opt_grading->getPgAuto() == 0) {
            $this->deleteByItem($item_id);

            return false;
        }

        $this->deleteByItem($item_id);

        $ar_s = [];
        $ar_u = [];
        $res_submission = $this->getServiceSubmission()->getList($item_id);
        
        foreach ($res_submission as $m_submission) {
            $ar_s[$m_submission->getId()] = [];
            foreach ($m_submission->getSubmissionUser() as $m_su) {
                $u = $m_su->getUserId();
                $ar_s[$m_submission->getId()][] = $u;
                $ar_u[] = $u;
            }
        }
        $nb = $m_opt_grading->getPgNb();
        while (($final = $this->_autoAssign($ar_u, $ar_s, $nb)) === false);
        foreach ($final as $s => $u) {
            $this->replace($s, $u);
        }
    }

    /**
     * Auto Assign peer (while === false)
     * 
     * @param array $ar_u
     * @param array $ar_s
     * @param int $nb
     * 
     * @return array
     */
    public function _autoAssign($ar_u, $ar_s, $nb)
    {
        $nbu = count($ar_u);
        $start = $ar_u;
        $final = [];
        foreach ($ar_s as $s_id => $s_user) {
            if (count($ar_u) === 0) {
                $ar_u = $start;
            }
            $tmp = $ar_u;
            foreach ($s_user as $uu) {
                $search = array_search($uu, $tmp);
                if ($search !== false) {
                    unset($tmp[$search]);
                }
            }
            if (count($tmp) >= $nb) {
                $keys = array_rand($tmp, $nb);
                if (!is_array($keys)) {
                    $keys = [$keys];
                }
                foreach ($keys as $k) {
                    $final[$s_id][] = $ar_u[$k];
                    unset($ar_u[$k]);
                }
            } elseif (count($ar_s) === count($start)) {
                return false;
            } else {
                $nbmin = count($tmp);
                $ar_u = $start;
                foreach ($tmp as $k => $t) {
                    $final[$s_id][] = $ar_u[$k];
                    unset($ar_u[$k]);
                }
                $tmp = $ar_u;
                foreach ($s_user as $uu) {
                    $search = array_search($uu, $tmp);
                    if ($search !== false) {
                        unset($tmp[$search]);
                    }
                }
                if (count($tmp) >= $nb) {
                    $keys = array_rand($tmp, $nb - $nbmin);
                    if (!is_array($keys)) {
                        $keys = [$keys];
                    }
                    foreach ($keys as $k) {
                        $final[$s_id][] = $ar_u[$k];
                        unset($ar_u[$k]);
                    }
                } else {
                    foreach ($tmp as $k => $t) {
                        $final[$s_id][] = $ar_u[$k];
                        unset($ar_u[$k]);
                    }
                }
            }
        }

        return $final;
    }

    /**
     * Get Service Submission
     * 
     * @return \Application\Service\Submission
     */
    private function getServiceSubmission()
    {
        return $this->getServiceLocator()->get('app_service_submission');
    }

    /**
     * Get Service OptGrading
     * 
     * @return \Application\Service\OptGrading
     */
    private function getServiceOptGrading()
    {
        return $this->getServiceLocator()->get('app_service_opt_grading');
    }
}
