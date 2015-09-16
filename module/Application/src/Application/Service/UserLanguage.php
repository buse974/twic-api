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
        $m_user_language->setUserId($this->getServiceAuth()
            ->getIdentity()
            ->getId())
            ->setLanguageId($language)
            ->setLanguageLevelId($language_level);
        
        if ($this->getMapper()->insert($m_user_language) <= 0) {
            throw new \Exception('Error insert');
        }
        
        return $this->getMapper()->getLastInsertValue();
    }

    /**
     * @invokable
     */
    public function update($id, $language_level, $language)
    {
        $m_user_language = $this->getModel()
            ->setId($id)
            ->setLanguageLevelId($language_level)
            ->setLanguageId($language);
        
        return $this->getMapper()->update($m_user_language);
    }

    /**
     * @invokable
     */
    public function get($user)
    {
        $m_user_langage = $this->getModel();
        $m_user_langage->setUserId($user);
        
        $languages = $this->getMapper()->select($m_user_langage);
        foreach ($languages as $language) {
            $m_language = $this->getServiceLanguage()->getModel();
            $m_language->setId($language->getLanguageId());
            $m_level = $this->getServiceLanguageLevel()->getModel();
            $m_level->setId($language->getLanguageLevelId());
            $language->setLanguage($this->getServiceLanguage()
                ->getMapper()
                ->select($m_language)
                ->current());
            $language->setLevel($this->getServiceLanguageLevel()
                ->getMapper()
                ->select($m_level)
                ->current());
        }
        
        return $languages;
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
        return $this->getMapper()->delete($this->getModel()
            ->setId($id)
            ->setUserId($this->getServiceAuth()
            ->getIdentity()
            ->getId()));
    }

    public function getServiceLanguage()
    {
        return $this->getServiceLocator()->get('app_service_language');
    }

    public function getServiceLanguageLevel()
    {
        return $this->getServiceLocator()->get('app_service_language_level');
    }

    /**
     *
     * @return \Auth\Service\AuthService
     */
    public function getServiceAuth()
    {
        return $this->getServiceLocator()->get('auth.service');
    }
}
