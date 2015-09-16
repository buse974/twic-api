<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class Question extends AbstractService
{
    /**
     * @param integer $component
     * 
     * @return \Application\Model\Question
     */
    public function getRand($component)
    {
        return $this->getMapper()->selectRand($component)->current();
    }
    
    public function getList($questionnaire)
    {
        return $this->getMapper()->getList($questionnaire);
    }
    
    /**
     * @invokable
     * 
     * @param string $text
     * @param string $component
     * 
     */
    public function add($text, $component)
    {
        $m_component = $this->getModel()->setText($text)->setComponentId($component);
        
        return $this->getMapper()->select($m_component);
    }
    
    /**
     * @invokable
     * 
     * @param string $id
     * @param string $text
     * @param string $component
     *
     */
    public function delete($id, $text, $component)
    {
        $m_component = $this->getModel()->setId($id)->setText($text)->setComponentId($component);
    
        return $this->getMapper()->update($m_component);
    }
}

