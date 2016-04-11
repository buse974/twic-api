<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class Submission extends AbstractService
{
    public function getByUserAndQuestionnaire($me, $questionnaire)
    {
        $m_submission = $this->getMapper()->getByUserAndQuestionnaire($me, $questionnaire);
    }
}