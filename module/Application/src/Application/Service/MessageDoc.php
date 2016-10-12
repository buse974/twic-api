<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Message Document 
 */
namespace Application\Service;

use Dal\Service\AbstractService;
use Application\Model\Library as ModelLibrary;

/**
 * Class MessageDoc.
 */
class MessageDoc extends AbstractService
{
    /**
     * Replace all document in message.
     * 
     * @param int   $message_id
     * @param array $document
     *
     * @return array
     */
    public function replace($message_id, $document)
    {
        $ret = [];
        if (null !== $document) {
            $m_message_doc = $this->getModel()->setMessageId($message_id);
            if (!is_array($document) || (is_array($document) && isset($document['token']))) {
                $document = [$document];
            } 
            $this->getMapper()->delete($m_message_doc);
            $m_message_doc->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
            foreach ($document as $d) { 
                $token = (isset($d['token'])) ? $d['token']:$d;
                $name = (isset($d['name'])) ? $d['name']:null;  
                $type = (isset($d['type'])) ? $d['type']:null;
                
                $m_library = $this->getServiceLibrary()->add($name,null,$token,$type,ModelLibrary::FOLDER_OTHER_INT);
                $m_message_doc->setToken($token)->setName($name)->setLibraryId($m_library->getId());
                
                $ret[$token] = $this->getMapper()->insert($m_message_doc);
            }
        }

        return $ret;
    }

    /**
     * Get List Message Document relation.
     *
     * @param int $message_id
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getList($message_id)
    {
        $res_message_doc = $this->getMapper()->select($this->getModel()->setMessageId($message_id));
        $ret = [];
        foreach ($res_message_doc as $m_message_doc) {
            if(!is_numeric($m_message_doc->getLibraryId())) {
                $ret[] = $m_message_doc->toArray();
            } else {
                $ret[] = $this->getServiceLibrary()->get($m_message_doc->getLibraryId())->toArray();
            }
        }
        
        return $ret;
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
