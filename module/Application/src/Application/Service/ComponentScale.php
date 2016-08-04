<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Component Scale
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class ComponentScale.
 */
class ComponentScale extends AbstractService
{
    /**
     * Add Component Scale.
     *
     * @invokable
     *
     * @param int    $component
     * @param int    $min
     * @param int    $max
     * @param string $describe
     * @param string $recommandation
     *
     * @return int
     */
    public function add($component, $min, $max, $describe, $recommandation)
    {
        if ($this->getMapper()->insert($this->getModel()
            ->setComponentId($component)
            ->setMin($min)
            ->setMax($max)
            ->setDescribe($describe)
            ->setRecommandation($recommandation)) <= 0) {
            throw new \Exception('error insert component scale');
        }

        return $this->getMapper()->getLastInsertValue();
    }

    /**
     * Delete Component Scale.
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
     * Update Component Scale.
     *
     * @invokable
     *
     * @param int    $id
     * @param int    $component
     * @param int    $min
     * @param int    $max
     * @param string $describe
     * @param string $recommandation
     *
     * @return int
     */
    public function update($id, $component, $min, $max, $describe, $recommandation)
    {
        return $this->getMapper()->update($this->getModel()
            ->setId($id)
            ->setComponentId($component)
            ->setMin($min)
            ->setMax($max)
            ->setDescribe($describe)
            ->setRecommandation($recommandation));
    }

    /**
     * Get List Component Scale.
     *
     * @invokable
     *
     * @param int   $component_id
     * @param array $filter
     *
     * @return array
     */
    public function getList($component_id = null, $filter = null)
    {
        $mapper = $this->getMapper();
        $res_component_scale = $mapper->usePaginator($filter)->getList($component_id);

        return ($filter !== null) ? ['count' => $mapper->count(), 'list' => $res_component_scale] : $res_component_scale;
    }
}
