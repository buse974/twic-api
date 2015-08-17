<?php
namespace Application\Service;

use Dal\Service\AbstractService;

class Resume extends AbstractService
{

    /**
     * Add experience.
     *
     * @invokable
     *
     * @param string $start_date            
     * @param string $end_date            
     * @param string $address            
     * @param string $logo            
     * @param string $title            
     * @param string $subtitle            
     * @param string $description            
     * @param integer $type            
     *
     * @throws \Exception
     */
    public function add($start_date = null, $end_date = null, $address = null, $logo = null, $title = null, $subtitle = null, $description = null, $type = null)
    {
        $m_education = $this->getModel();
        
        $m_education->setAddress($address)
            ->setLogo($logo)
            ->setStartDate($start_date)
            ->setEndDate($end_date)
            ->setTitle($title)
            ->setSubtitle($subtitle)
            ->setDescription($description)
            ->setType($type)
            ->setUserId($this->getServiceUser()
            ->getIdentity()['id']);
        
        if ($this->getMapper()->insert($m_education) <= 0) {
            throw new \Exception('error insert experience');
        }
        
        return $this->getMapper()->getLastInsertValue();
    }

    /**
     * Update experience.
     *
     * @invokable
     * 
     * @param integer $id
     * @param string $start_date
     * @param string $end_date
     * @param string $address
     * @param string $logo
     * @param string $title
     * @param string $subtitle
     * @param string $description
     * @param string $type
     * 
     * @return integer
     */
    public function update($id, $start_date = null, $end_date = null, $address = null, $logo = null, $title = null, $subtitle = null, $description = null, $type = null)
    {
        $m_education = $this->getModel();
        
        $m_education->setAddress($address)
            ->setLogo($logo)
            ->setStartDate($start_date)
            ->setEndDate($end_date)
            ->setTitle($title)
            ->setSubtitle($subtitle)
            ->setDescription($description)
            ->setType($type)
            ->setUserId($this->getServiceUser()
            ->getIdentity()['id']);
        
        return $this->getMapper()->update($m_education, array('id' => $id,'user_id' => $this->getServiceUser()
            ->getIdentity()['id']));
    }

    /**
     * Update education experience.
     *
     * @invokable
     *
     * @param int $id            
     *
     * @return int
     */
    public function delete($id)
    {
        $m_education = $this->getModel();
        
        $m_education->setId($id)->setUserId($this->getServiceUser()
            ->getIdentity()['id']);
        
        return $this->getMapper()->delete($m_education);
    }

    /**
     *
     * @return \Application\Service\User
     */
    public function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }
}