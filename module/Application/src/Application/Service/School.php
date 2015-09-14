<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class School extends AbstractService
{
    /**
     * add school.
     *
     * @invokable
     *
     * @param string $name
     * @param string $logo
     *
     * @throws \Exception
     *
     * @return int
     */
    public function add($name, $next_name = null, $short_name = null, $logo = null, $describe = null, $website = null,
            $programme = null, $background = null, $phone = null, $contact = null, $contact_id = null, $address = null)
    {
        $m_school = $this->getModel();
        $m_school->setName($name)
                 ->setNextName($next_name)
                 ->setShortName($short_name)
                 ->setLogo($logo)
                 ->setDescribe($describe)
                 ->setWebsite($website)
                 ->setProgramme($programme)
                 ->setBackground($background)
                 ->setPhone($phone)
                 ->setContact($contact)
                 ->setContactId($contact_id);

        if ($address !== null) {
            $address_id = $this->getServiceAddress()->getAddress($address)->getId();
            if ($address_id !== null) {
                $m_school->setAddressId($address_id);
            }
        }

        if ($this->getMapper()->insert($m_school) <= 0) {
            throw new \Exception('error insert');
        }

        $school_id = $this->getMapper()->getLastInsertValue();
        
        $this->getServiceEvent()->schoolNew($school_id);
        $this->getServiceGrading()->initTpl($school_id);

        return $this->get($school_id);
    }

    /**
     * Update school.
     *
     * @invokable
     *
     * @param int    $id
     * @param string $name
     * @param string $logo
     *
     * @return int
     */
    public function update($id, $name, $logo)
    {
        $m_school = $this->getModel();

        $m_school->setId($id)
                 ->setName($name)
                 ->setLogo($logo);

        return $this->getMapper()->update($m_school);
    }

    /**
     * Get school by ID.
     *
     * @invokable
     *
     * @param int $id
     *
     * @return \Application\Model\School
     */
    public function get($id)
    {
        $results = $this->getMapper()->get($id);

        if ($m_school = $results->count() <= 0) {
            throw new \Exception('not school with id:'.$id);
        }

        return $results->current();
    }

    /**
     * Get school list.
     *
     * @param string $filter
     * @param string $search
     *
     * @invokable
     *
     * @return array
     */
    public function getList($filter = null, $search = null)
    {
        $mapper = $this->getMapper();
        $res = $mapper->usePaginator($filter)->getList($filter, $search);

        return array('list' => $res,
                    'count' => $mapper->count());
    }

    /**
     * Delete school.
     *
     * @invokable
     *
     * @param int $id
     *
     * @return int
     */
    public function delete($id)
    {
        $ret = array();

        if (!is_array($id)) {
            $id = array($id);
        }

        foreach ($id as $i) {
            $m_school = $this->getModel()->setDeletedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'))->setId($i);
            $ret[$i] = $this->getMapper()->update($m_school);
        }

        return $ret;
    }

    /**
     * @return \Application\Service\Event
     */
    public function getServiceEvent()
    {
        return $this->getServiceLocator()->get('addr_service_event');
    }
    
    /**
     * @return \Address\Service\Address
     */
    public function getServiceAddress()
    {
        return $this->getServiceLocator()->get('addr_service_address');
    }

    /**
     * @return \Application\Service\Grading
     */
    public function getServiceGrading()
    {
        return $this->getServiceLocator()->get('app_service_grading');
    }
}
