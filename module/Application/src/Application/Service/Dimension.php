<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Dimension
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class Dimension.
 */
class Dimension extends AbstractService
{
    /**
     * Get List Dimension.
     * 
     * @invokable
     * 
     * @param array  $filter
     * @param string $search
     *
     * @return array
     */
    public function getList($filter = null, $search = null)
    {
        $mapper = $this->getMapper();
        $res_dimension = $mapper->usePaginator($filter)->getList($search);

        foreach ($res_dimension as $m_dimension) {
            $res_component = $this->getServiceComponent()->getList($m_dimension->getId());
            $m_dimension->setComponent($res_component->count() ? $res_component : array());
        }

        return array('count' => $mapper->count(), 'list' => $res_dimension);
    }

    /**
     * Add Dimnsion.
     *
     * @invokable
     *
     * @param string $name
     * @param string $describe
     *
     * @throws \Exception
     *
     * @return int
     */
    public function add($name, $describe)
    {
        $m_dimension = $this->getModel()
            ->setName($name)
            ->setDescribe($describe);

        if ($this->getMapper()->insert($m_dimension) <= 0) {
            throw new \Exception('error insert dimension');
        }

        return $this->getMapper()->getLastInsertValue();
    }

    /**
     * Update Dimension.
     *
     * @invokable
     *
     * @param int    $id
     * @param string $name
     * @param string $describe
     *
     * @return int
     */
    public function update($id, $name, $describe)
    {
        $m_dimension = $this->getModel()
            ->setId($id)
            ->setName($name)
            ->setDescribe($describe);

        return $this->getMapper()->update($m_dimension);
    }

    /**
     * Delete Dimension (update deleted date ).
     *
     * @invokable
     *
     * @param int $id
     *
     * @return int
     */
    public function delete($id)
    {
        $m_dimension = $this->getModel()
            ->setId($id)
            ->setDeletedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));

        return $this->getMapper()->update($m_dimension);
    }

    /**
     * Get EqCq By School.
     * 
     * @invokable
     * 
     * @param int $school
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getEqCq($school)
    {
        return $this->getMapper()->getEqCq($school);
    }

    /**
     * Get Service Component.
     * 
     * @return \Application\Service\Component
     */
    private function getServiceComponent()
    {
        return $this->container->get('app_service_component');
    }
}
