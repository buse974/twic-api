<?php
/**
 *
 * TheStudnet (http://thestudnet.com)
 *
 * User Language
 *
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class UserLanguage
 */
class UserLanguage extends AbstractService
{

    /**
     * Add Language to User
     *
     * @invokable
     * 
     * @param int $language
     * @param int $language_level
     * @throws \Exception
     * @return int
     */
    public function add($language, $language_level)
    {
        $m_user_language = $this->getModel();
        $m_user_language->setUserId($this->getServiceUser()
            ->getIdentity()['id'])
            ->setLanguageId($language)
            ->setLanguageLevelId($language_level);
        
        if ($this->getMapper()->insert($m_user_language) <= 0) {
            throw new \Exception('Error insert');
        }
        
        return $this->getMapper()->getLastInsertValue();
    }

    /**
     * Update Language
     *
     * @invokable
     *
     * @param int $id            
     * @param int $language_level            
     * @return int
     */
    public function update($id, $language_level)
    {
        $m_user_language = $this->getModel()->setLanguageLevelId($language_level);
        
        return $this->getMapper()->update($m_user_language, ['id' => $id,'user_id' => $this->getServiceUser()
            ->getIdentity()['id']]);
    }

    /**
     * Get Language of user
     *
     * @invokable
     *
     * @param int $user            
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function get($user)
    {
        $m_user_langage = $this->getModel();
        $m_user_langage->setUserId($user);
        
        $res_user_language = $this->getMapper()->select($m_user_langage);
        foreach ($res_user_language as $language) {
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
        
        return $res_user_language;
    }

    /**
     * Delete Language User
     *
     * @invokable
     *
     * @param int $id            
     * @return int
     */
    public function delete($id)
    {
        return $this->getMapper()->delete($this->getModel()
            ->setId($id)
            ->setUserId($this->getServiceUser()
            ->getIdentity()['id']));
    }

    /**
     * Get Service Language
     *
     * @return \Application\Service\Language
     */
    private function getServiceLanguage()
    {
        return $this->getServiceLocator()->get('app_service_language');
    }

    /**
     * Get Service LanguageLevel
     *
     * @return \Application\Service\LanguageLevel
     */
    private function getServiceLanguageLevel()
    {
        return $this->getServiceLocator()->get('app_service_language_level');
    }

    /**
     * Get Service User
     *
     * @return \Application\Service\User
     */
    private function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }
}
