<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Circle
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class Circle
 */
class Circle extends AbstractService
{

    /**
     * Add circle
     *
     * @invokable
     *
     * @param string $name            
     * @throws \Exception
     * @return int
     */
    public function add($name)
    {
        if ($this->getMapper()->insert($this->getModel()
            ->setName($name)) <= 0) {
            throw new \Exception('error insert circle');
        }
        
        return $this->getMapper()->getLastInsertValue();
    }

    /**
     * Update Circle
     *
     * @invokable
     *
     * @param int $id            
     * @param string $name            
     * @return int
     */
    public function update($id, $name)
    {
        return $this->getMapper()->update($this->getModel()
            ->setId($id)
            ->setName($name));
    }

    /**
     * Remove Circle
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
     * Get Circle
     *
     * @invokable
     *
     * @param int $id
     * @return \Application\Model\Circle
     */
    public function get($id)
    {
        return $this->getMapper()->select($this->getModel()
            ->setId($id))->current();
    }
    
    /**
     * Get List Circle
     *
     * @invokable
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getList()
    {
        return $this->getMapper()->fetchAll();
    }
}