<?php
/**
 * 
 * TheStudnet (http://thestudnet.com)
 *
 * Material
 *
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class Material
 */
class Material extends AbstractService
{
    /**
     * Add Material
     *
     * @invokable
     * 
     * @param int $course_id
     * @param string $name
     * @param string $type
     * @param string $link
     * @param string $token
     * @throws \Exception
     * @return \Application\Model\Library|\Dal\Db\ResultSet\ResultSet
     */
    public function add($course_id, $name = null, $type = null, $link = null, $token = null)
    {
        if (null === $link && null === $token && null === $name) {
            return 0;
        }
        
        $library_id = $this->getServiceLibrary()
            ->add($name, $link, $token, $type)
            ->getId();
        
        $m_material = $this->getModel()
            ->setCourseId($course_id)
            ->setLibraryId($library_id);

        if ($this->getMapper()->insert($m_material) <= 0) {
            throw new \Exception('error insert material relation');
        }

        return $this->getServiceLibrary()->get($library_id);
    }
    
    /**
     * Delete Document
     *
     * @invokable
     *
     * @param int $library_id
     * @return int
     */
    public function delete($library_id)
    {
        $ret = $this->getMapper()->delete($this->getModel()
            ->setLibraryId($library_id));
        
        if($ret > 0) {
            $this->getServiceLibrary()->delete($library_id);
        }
        
        return $ret;
    }
    
    /**
     * Get List Materials
     *
     * @invokable
     *
     * @param int $course_id
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getList($course_id)
    {
        return $this->getServiceLibrary()->getListMaterials($course_id);
    }
    
    /**
     * Get Service Library
     *
     * @return \Application\Service\Library
     */
    private function getServiceLibrary()
    {
        return $this->container->get('app_service_library');
    }
}