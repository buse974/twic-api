<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class SubQuestion extends AbstractService
{
    /**
     * @param integer $sub_quiz_id
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListLite($sub_quiz_id)
    {
        return $this->getMapper()->select($this->getModel()->setSubQuizId($sub_quiz_id));
    }
}