<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class Division extends AbstractService
{
    /**
     * @invokable
     */
    public function add($name, $libelle, $code, $division_id, $country_id, $latitude, $longitude)
    {
        $m_city = $this->getModel();
        $m_city->setName($name)
                 ->setLibelle($libelle)
                 ->setCode($code)
                 ->setDivision_id($division_id)
                 ->setCountryId($country_id)
                 ->setLatitude($latitude)
                 ->setLongitude($longitude);

        if ($division_id !== null) {
            $division = $this->getServiceAddress()->getDivisionId($division_id)->getId();
            if ($division !== null) {
                $m_city->setDivisionId($division);
            }
        }

        if ($this->getMapper()->insert($m_city) <= 0) {
            throw new \Exception('error insert');
        }

        $city_id = $this->getMapper()->getLastInsertValue();
        $this->getServiceGrading()->initTpl($city_id);

        return $this->get($city_id);
    }

    /**
     * @invokable
     */
    public function getList($filter = null)
    {
        $mapper = $this->getMapper();
        $res_division = $mapper->usePaginator($filter)->getList();

        return array('count' => $mapper->count(),'list' => $res_division);
    }

    /**
     * @invokable
     */
    public function delete()
    {
        $ret = array();

        if (!is_array($id)) {
            $id = array($id);
        }

        $m_course = $this->getModel()->setDeletedDate((new DateTime('now', new DateTimeZone('UTC')))->format('Y-m-d H:i:s'));

        foreach ($id as $idc) {
            if ($ret[$idc] = $this->getMapper()->update($m_course, array('id' => $idc)) > 0) {
                $this->getServiceMaterialDocument()->deleteByCourseId($idc);
            }
        }

        return $ret;
    }

    /**
     * @invokable
     */
    public function update()
    {
        $m_address = $this->getModel();

        $m_address->setId($id)
                 ->setName($name)
                 ->setLogo($logo);

        return $this->getMapper()->update($m_address);
    }
}
