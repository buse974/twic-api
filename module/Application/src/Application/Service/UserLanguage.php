<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class UserLanguage extends AbstractService
{
    /**
     * @invokable
     */
    public function add($language, $language_level)
    {
        $m_user_language = $this->getModel();
        $m_user_language->setUserId($this->getServiceAuth()->getIdentity()->getId())
                        ->setLanguageId($language)
                        ->setLanguageLevelId($language_level);

        if ($this->getMapper()->insert($m_user_language) <= 0) {
            throw new \Exception('Error insert');
        }

        return $this->getMapper()->getLastInsertValue();
    }

    /**
     * @invokable
     *
     * @param int $id
     *
     * @return int
     */
    public function delete($id)
    {
        return $this->getMapper()->delete($this->getModel()->setId($id)->setUserId($this->getServiceAuth()->getIdentity()->getId()));
    }

    /**
     * @return \Auth\Service\AuthService
     */
    public function getServiceAuth()
    {
        return $this->getServiceLocator()->get('auth.service');
    }
}
