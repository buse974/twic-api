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
     * Get List 
     * 
     * @invokable
     * 
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getList()
    {
        return $this->getMapper()->fetchAll();
    }
}
