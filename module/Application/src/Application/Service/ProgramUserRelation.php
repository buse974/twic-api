<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Program User Relation
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class ProgramUserRelation.
 */
class ProgramUserRelation extends AbstractService
{
    /**
     * Add Users to Program.
     * 
     * @param array $user
     * @param array $program
     *
     * @return array
     */
    public function add($user, $program)
    {
        $ret = [];

        foreach ($user as $u) {
            foreach ($program as $p) {
                $ret[$u][$p] = $this->getMapper()->insertUserProgram($p, $u);
            }
        }

        return $ret;
    }

    /**
     * Delete User To Program.
     * 
     * @param array $user
     * @param array $program
     *
     * @return array
     */
    public function deleteProgram($user, $program)
    {
        $ret = array();

        if (!is_array($user)) {
            $user = array($user);
        }

        if (!is_array($program)) {
            $program = array($program);
        }

        foreach ($user as $u) {
            foreach ($program as $p) {
                $ret[$u][$p] = $this->getMapper()->delete($this->getModel()->setProgramId($p)->setUserId($u));
            }
        }

        return $ret;
    }

    /**
     * Delete user of all program.
     * 
     * @param int $user_id
     *
     * @return int
     */
    public function deleteByUser($user)
    {
        return $this->getMapper()->delete($this->getModel()->setUserId($user));
    }
}
