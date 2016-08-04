<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Dimension Scale
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class DimensionScale.
 */
class DimensionScale extends AbstractService
{
    /**
     * Add Dimension Scale.
     * 
     * @invokable
     *
     * @param int    $dimension
     * @param int    $min
     * @param int    $max
     * @param string $describe
     *
     * @return int
     */
    public function add($dimension, $min, $max, $describe)
    {
        if ($this->getMapper()->insert($this->getModel()
            ->setDimensionId($dimension)
            ->setMin($min)
            ->setMax($max)
            ->setDescribe($describe)) <= 0) {
            throw new \Exception('error insert scale');
        }

        return $this->getMapper()->getLastInsertValue();
    }

    /**
     * Delete Dimension Scale.
     * 
     * @invokable
     *
     * @param int $id
     *
     * @return int
     */
    public function delete($id)
    {
        return $this->getMapper()->delete($this->getModel()
            ->setId($id));
    }

    /**
     * Update Get Dimension Scale.
     * 
     * @invokable
     *
     * @param int    $id
     * @param int    $dimension
     * @param int    $min
     * @param int    $max
     * @param string $describe
     *
     * @return int
     */
    public function update($id, $dimension, $min, $max, $describe)
    {
        return $this->getMapper()->update($this->getModel()
            ->setId($id)
            ->setDimensionId($dimension)
            ->setMin($min)
            ->setMax($max)
            ->setDescribe($describe));
    }

    /**
     * Get List Dimension Scale.
     * 
     * @invokable
     *
     * @param array $filter
     *
     * @return array
     */
    public function getList($filter = null)
    {
        $mapper = $this->getMapper();
        $res_dimension_scale = $mapper->usePaginator($filter)->getList();

        return ($filter !== null) ? ['count' => $mapper->count(), 'list' => $res_dimension_scale] : $res_dimension_scale;
    }
}
