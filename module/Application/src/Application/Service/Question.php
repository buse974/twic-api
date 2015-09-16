<?php
namespace Application\Service;

use Dal\Service\AbstractService;

class Question extends AbstractService
{

    /**
     *
     * @param integer $component            
     *
     * @return \Application\Model\Question
     */
    public function getRand($component)
    {
        return $this->getMapper()
            ->selectRand($component)
            ->current();
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
        $m_component = $this->getModel()
            ->setText($text)
            ->setComponentId($component);
        
        if ($this->getMapper()->insert($m_component) <= 0) {
            throw new \Eception('error insert question');
        }
        
        return $this->getMapper()->getLastInsertValue();
    }

    /**
     * @invokable
     *
     * @param integer $id            
     * @param string $text            
     * @param string $component            
     *
     */
    public function update($id, $text, $component)
    {
        $m_component = $this->getModel()
            ->setId($id)
            ->setText($text)
            ->setComponentId($component);
        
        return $this->getMapper()->update($m_component);
    }

    /**
     * @invokable
     *
     * @param integer $id            
     *
     */
    public function delete($id)
    {
        $m_component = $this->getModel()
            ->setId($id)
            ->setDeletedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
        
        return $this->getMapper()->update($m_component);
    }
}

