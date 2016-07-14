<?php
/**
 * 
 * TheStudnet (http://thestudnet.com)
 *
 * Language Level
 *
 */

namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class LanguageLevel
 */
class LanguageLevel extends AbstractService
{
    /**
     * @invokable
     */
    public function getList()
    {
        return $this->getMapper()->fetchAll();
    }
}
