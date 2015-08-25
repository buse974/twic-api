<?php
namespace Application\Service;

use Dal\Service\AbstractService;

class Address extends AbstractService
{

    /**
     * @invokable
     *      
     */
    public function add()
    {
        
    }

  
    /**
     * @invokable
     * 
     */
    public function getList($filter = null)
    {
        $mapper = $this->getMapper();
        $res_addr = $mapper->usePaginator($filter)->getList();
              
        return array('count' => $mapper->count(),'list' => $res_addr);
    }

    /**
     * @invokable
     * 
     */
    public function delete()
    {
        $ret = array();
        
        if (! is_array($id)) {
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
     * 
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
