<?php
/**
 * 
 * TheStudnet (http://thestudnet.com)
 *
 * Scale
 *
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class Scale
 */
class Scale extends AbstractService
{
    /**
     * Add Scale
     * 
     * @invokable
     *
     * @param string $name
     * @param string $value
     * @return int
     */
    public function add($name, $value)
    {
        if ($this->getMapper()->insert($this->getModel()
            ->setName($name)
            ->setValue($value)) <= 0) {
            throw new \Exception('error insert scale');
        }

        return $this->getMapper()->getLastInsertValue();
    }

    /**
     * Delete Scale
     * 
     * @invokable
     *
     * @param int $id
     * @return int
     */
    public function delete($id)
    {
        return $this->getMapper()->delete($this->getModel()
            ->setId($id));
    }

    /**
     * Update Scale
     * 
     * @invokable
     *
     * @param int    $id
     * @param string $name
     * @param string $value
     * @return int
     */
    public function update($id, $name, $value)
    {
        return $this->getMapper()->update($this->getModel()
            ->setId($id)
            ->setName($name)
            ->setValue($value));
    }

    /**
     * Get List Scale
     * 
     * @invokable
     * @param array $filter
     * @return array|\Dal\Db\ResultSet\ResultSet
     */
    public function getList($filter = null)
    {
        $mapper = $this->getMapper();
        $res_scale = $mapper->usePaginator($filter)->fetchAll();

        return ($filter !== null) ? ['count' => $mapper->count(), 'list' => $res_scale] : $res_scale;
    }
}
