<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class Tag extends AbstractService
{
    /**
     * Add tag
     *
     * @param  string $name
     * @return int
     */
    public function add($name)
    {
        $res_tag = $this->getMapper()->select($this->getModel()->setName($name));
        if ($res_tag->count() <= 0) {
            $this->getMapper()->insert($this->getModel()->setName($name)->setWeight(1));
            $id = $this->getMapper()->getLastInsertValue();
        } else {
            $m_tag = $res_tag->current();
            $this->getMapper()->update($this->getModel()->setId($m_tag->getId())->setWeight($m_tag->getWeight()+1));
            $id = $m_tag->getId();
        }

        return $id;
    }

    /**
     *
     * Get list of tags
     *
     * @invokable
     * @param string $search
     * @param string $category
     * @param array|string $exclude
     * @param array $filter
     */
    public function getList($search, $category = null, $exclude = null, $filter = null)
    {
        $mapper = $this->getMapper();
        if(null !== $filter){
          $mapper->usePaginator($filter);
        }
        return $mapper->getList($search, $category, $exclude);
    }

    /**
     *
     * @param int $page_id
     */
    public function getListByPage($page_id)
    {
        return $this->getMapper()->getListByPage($page_id);
    }

    /**
     *
     * @param int $user_id
     */
    public function getListByUser($user_id)
    {
        return $this->getMapper()->getListByUser($user_id);
    }
}
