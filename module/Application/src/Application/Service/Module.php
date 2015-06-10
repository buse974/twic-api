<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class Module extends AbstractService
{
    /**
     * Add Module.
     *
     * @invokable
     *
     * @param int $course
     *
     * @throws \Exception
     *
     * @return int
     */
    public function add($course, $title = null)
    {
        $res_course = $this->getMapper()->insert($this->getModel()->setCourseId($course)->setTitle($title));

        if ($res_course <= 0) {
            throw new \Exception('error insert module');
        }

        return $this->getMapper()->getLastInsertValue();
    }

    /**
     * Add Module.
     * @invokable
     *
     * @param int    $id
     * @param string $title
     *
     * @return int
     */
    public function update($id, $title)
    {
        return $this->getMapper()->update($this->getModel()->setId($id)->setTitle($title));
    }
    
    /**
     * @invokable
     * 
     * @param integer $course
     * @return array
     */
    public function getList($course)
    {
    	return $this->getMapper()->select($this->getModel()->setCourseId($course));
    }
}
