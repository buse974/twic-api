<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Submission Paire grader
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class SubmissionPg.
 */
class SubmissionPg extends AbstractService
{
    /**
     * Add Submission Speed Grader.
     * 
     * @param int $submission_id
     * @param int $user_id
     *
     * @return number
     */
    public function add($submission_id, $user_id)
    {
        $date = (new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s');

        return  $this->getMapper()->insert($this->getModel()->setUserId($user_id)->setSubmissionId($submission_id)->setDate($date));
    }

    /**
     * Delete Submission Speed Grader.
     * 
     * @param int $submission_id
     * @param int $user_id
     *
     * @return int
     */
    public function delete($submission_id, $user_id)
    {
        return  $this->getMapper()->delete($this->getModel()->setUserId($user_id)->setSubmissionId($submission_id));
    }

    /**
     * Get List Submission Speed Grader.
     * 
     * @invokable
     * 
     * @param int $item_id
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListByItem($item_id)
    {
        return  $this->getMapper()->getListByItem($item_id);
    }

    /**
     * Get List.
     * 
     * @param int $submission_id
     * @param int $user_id
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListBySubmission($submission_id, $user_id = null)
    {
        $m_submission_pg = $this->getModel()->setSubmissionId($submission_id);

        if (null !== $user_id) {
            $m_submission_pg->setUserId($user_id);
        }

        return  $this->getMapper()->select($m_submission_pg);
    }

    /**
     * Delete By Item.
     * 
     * @param int $item_id
     *
     * @return bool
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
     * Check Grader.
     * 
     * @param int $submission_id
     * @param int $user_id
     *
     * @return int
     */
    public function checkGraded($submission_id, $user_id)
    {
        return  $this->getMapper()->checkGraded($submission_id, $user_id);
    }

    /**
     * Replace submission peer grader.
     * 
     * @param int   $submission_id
     * @param array $user_id
     *
     * @return int
     */
    public function replace($submission_id, $users)
    {
        $this->getMapper()->deleteNotIn($submission_id, $users);
        foreach ($users as $u) {
            $this->add($submission_id, $u);
        }

        return 1;
    }

    /**
     * Auto Assign peer.
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
        $final = $this->_autoAssign($ar_u, $ar_s, $nb);
        foreach ($final as $s => $p) {
            $pg = $p['pgs'];
            if (!empty($pg)) {
                $this->replace($s, $pg);
            }
        }
    }

    /**
     * Auto Assign peer (while === false).
     * 
     * @param array $ar_u
     * @param array $ar_s
     * @param int   $nb
     * 
     * @return array
     */
    public function _autoAssign($users, $submissions, $nb)
    {
        $obj = [];

        // Creation d'un objet indexant le tableau users & pairgraders d'une soumission par submission_id.
        foreach ($submissions as $submission_id => $v) {
            $obj[$submission_id] = [
                'users' => $submissions[$submission_id],
                'pgs' => [],
            ];
        }

        $c_user = count($users);
        // On boucle pour chaque pair grader qu'on doit rajouter
        for ($i = 0;$i < $nb;++$i) {
            // POur chaque PG à ajouter, le but est de passer sur chaque soumission et d'ajouter un PG.
            foreach ($submissions as $submission_id => $v) {
                // On boucle sur les users pour trouver celui à ajouter comme PG à la soumission
                for ($n = 0;$n < $c_user;++$n) {
                    // Si le user ne fait pas parti des user de la soumission, il est candidat à l'ajout.
                    if (!in_array($users[$n], $obj[$submission_id]['users'])) {
                        $valid = true;
                        // Si le nombre de soumissions est impair il faut check de pas ajouter A comme PG de B & B comme PG de A car C peut rester comme une bite...
                        if (count($obj) % 2) {
                            foreach ($obj as $sid => $v) {
                                if (in_array($users[$n], $obj[$sid]['users'])) {
                                    $valid = true;
                                    foreach ($obj[$sid]['pgs'] as $uid) {
                                        $valid = !in_array($uid, $obj[$submission_id]['users']);
                                        if ($valid === false) {
                                            break;
                                        }
                                    }
                                    break;
                                }
                            }
                        }
                        // Le user est ajouté aux PG, puis on replace celui-ci en fin du tableau 'users' pour qu'il ne soit plus ajouté avant le prochain round.
                        if ($valid) {
                            $obj[$submission_id]['pgs'][] = $users[$n];
                            $users[] = current(array_splice($users, $n, 1));
                            break;
                        }
                    }
                }
            }
        }

        return $obj;
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
     * Get Service OptGrading.
     * 
     * @return \Application\Service\OptGrading
     */
    private function getServiceOptGrading()
    {
        return $this->getServiceLocator()->get('app_service_opt_grading');
    }
}
