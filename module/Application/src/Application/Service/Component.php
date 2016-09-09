<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Component
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class Component.
 */
class Component extends AbstractService
{
    /**
     * Get List Component.
     *
     * @invokable
     *
     * @param int    $dimension
     * @param array  $filter
     * @param string $search
     *
     * @return array
     */
    public function getList($dimension = null, $filter = null, $search = null)
    {
        $mapper = $this->getMapper();

        $res_component = $mapper->usePaginator($filter)->getList($dimension, $search);

        return (null !== $filter) ? array('count' => $mapper->count(), 'list' => $res_component) : $res_component;
    }

    /**
     * Get List Component With Scale.
     *
     * @invokable
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListWithScale()
    {
        $res_component = $this->getMapper()->fetchAll();

        foreach ($res_component as $m_component) {
            $m_component->setComponentScales($this->getServiceComponentScale()
                ->getList($m_component->getId()));
        }

        return $res_component;
    }

    /**
     * Add Component.
     *
     * @invokable
     *
     * @param string $name
     * @param string $dimension
     * @param string $describe
     *
     * @return int
     */
    public function add($name, $dimension, $describe)
    {
        $m_component = $this->getModel()
            ->setName($name)
            ->setDimensionId($dimension)
            ->setDescribe($describe);

        if ($this->getMapper()->insert($m_component) <= 0) {
            throw new \Exception('error insert component');
        }

        return $this->getMapper()->getLastInsertValue();
    }

    /**
     * Get EqCq.
     * 
     * @invokable
     *
     * @param int          $school
     * @param string       $gender
     * @param array|string $nationality
     * @param array|string $origin
     * @param array|string $program
     *
     * @return array
     */
    public function getEqCq($school, $gender = null, $nationality = null, $origin = null, $program = null)
    {
        $ret = ['stats' => $this->getMapper()
            ->getEqCq($school, $gender, $nationality, $origin, $program)
            ->toArray(), 'description' => $this->getMapper()
            ->getEqCqStat($school, $gender, $nationality, $origin, $program)
            ->current(), ];
        $ret['description']['genre'] = (!empty($ret['description']['genre'])) ? json_decode($ret['description']['genre']) : [];
        $ret['description']['nationality'] = (!empty($ret['description']['nationality'])) ? json_decode($ret['description']['nationality']) : [];
        $ret['description']['origin'] = (!empty($ret['description']['origin'])) ? json_decode($ret['description']['origin']) : [];

        return $ret;
    }

    /**
     * Get List EqCq.
     * 
     * @invokable
     *
     * @param array        $school
     * @param string       $gender
     * @param array|string $nationality
     * @param array|string $origin
     * @param array|string $program
     *
     * @return array
     */
    public function getListEqCq($schools, $gender = null, $nationality = null, $origin = null, $program = null)
    {
        $nbr_school = $this->getServiceUser()->nbrBySchool($schools);

        $ns = [];
        foreach ($nbr_school as $nbr) {
            $ns[$nbr->getSchoolId()][] = $nbr->toArray();
        }

        $ret = [];
        foreach ($schools as $school) {
            $ret[$school] = ['eqcq' => $this->getEqCq($school, $gender, $nationality, $origin, $program), 'nbr' => $ns[$school]];
        }

        return $ret;
    }

    /**
     * Update Component.
     *
     * @invokable
     *
     * @param int    $id
     * @param string $name
     * @param string $dimension
     * @param string $describe
     *
     * @return int
     */
    public function update($id, $name, $dimension, $describe)
    {
        $m_component = $this->getModel()
            ->setId($id)
            ->setName($name)
            ->setDimensionId($dimension)
            ->setDescribe($describe);

        return $this->getMapper()->update($m_component);
    }

    /**
     * Delete Component.
     *
     * @invokable
     *
     * @param int $id
     *
     * @return int
     */
    public function delete($id)
    {
        $m_component = $this->getModel()
            ->setId($id)
            ->setDeletedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));

        return $this->getMapper()->update($m_component);
    }

    /**
     * Get Service ComponentScale.
     *
     * @return \Application\Service\ComponentScale
     */
    private function getServiceComponentScale()
    {
        return $this->container->get('app_service_component_scale');
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
}
