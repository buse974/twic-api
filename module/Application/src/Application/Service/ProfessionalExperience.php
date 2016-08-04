<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Professional Experience
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class ProfessionalExperience.
 */
class ProfessionalExperience extends AbstractService
{
    /**
     * Add education experience.
     *
     * @invokable
     *
     * @param string $date
     * @param string $address
     * @param string $logo
     * @param string $title
     * @param string $description
     *
     * @return int
     */
    public function add($date, $address, $logo, $title, $description)
    {
        $m_education = $this->getModel();

        $m_education->setDate($date)
            ->setAddress($address)
            ->setLogo($logo)
            ->setTitle($title)
            ->setDescription($description)
            ->setUserId($this->getServicUser()
            ->getIdentity()['id']);

        if ($this->getMapper()->insert($m_education) <= 0) {
            throw new \Exception('error insert');
        }

        return $this->getMapper()->getLastInsertValue();
    }

    /**
     * Update education experience.
     *
     * @invokable
     *
     * @param int    $id
     * @param string $date
     * @param string $address
     * @param string $logo
     * @param string $title
     * @param string $description
     *
     * @return int
     */
    public function update($id, $date, $address, $logo, $title, $description)
    {
        $m_education = $this->getModel();

        $m_education->setDate($date)
            ->setAddress($address)
            ->setLogo($logo)
            ->setTitle($title)
            ->setDescription($description);

        return $this->getMapper()->update($m_education, array('id' => $id, 'user_id' => $this->getServicUser()
            ->getIdentity()['id'], ));
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

        $m_education->setId($id)->setUserId($this->getServicUser()
            ->getIdentity()['id']);

        return $this->getMapper()->delete($m_education);
    }

    /**
     * Get Service User.
     * 
     * @return \Application\Service\User
     */
    private function getServicUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }
}
