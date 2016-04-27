<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class SubAnswer extends AbstractService
{
    /**
     * @param integer $sub_question_id
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListLite($sub_question_ids)
    {
        return $this->getMapper()->select($this->getModel()->setSubQuestionId($sub_question_ids));
    }
}