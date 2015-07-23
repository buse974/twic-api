<?php
namespace Application\Service;

use Dal\Service\AbstractService;

class VideoconfDoc extends AbstractService
{

    /**
     * @invokable
     * 
     * @param string $name            
     * @param string $token            
     * @param integer $videoconf            
     * @throws \Exception
     *
     * @return integer
     */
    public function add($name, $token, $videoconf)
    {
        $m_videoconf_doc = $this->getModel()
            ->setToken($token)
            ->setName($name)
            ->setVideoconfId($videoconf)
            ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
        
        if ($this->getMapper()->insert($m_videoconf_doc) <= 0) {
            throw new \Exception('Error insert');
        }
        
        return $this->getMapper()->getLastInsertValue();
    }

    /**
     * @invokable
     * 
     * @param integer $videoconf            
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListByVideoconf($videoconf)
    {
        $m_videoconf_doc = $this->getModel()->setVideoconfId($videoconf);
        
        return $this->getMapper()->select($m_videoconf_doc);
    }

    /**
     *
     * @invokable
     * 
     * @param integer $id            
     *
     * @return integer
     */
    public function delete($id)
    {
        $m_videoconf_doc = $this->getModel()->setId($videoconf);
        
        return $this->getMapper()->delete($m_videoconf_doc);
    }
}