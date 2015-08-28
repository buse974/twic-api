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
}