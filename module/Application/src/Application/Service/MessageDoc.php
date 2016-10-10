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
        $m_message_doc = $this->getModel()->setMessageId($message_id);

        $ret = [];
        if (null !== $document) {
            if (!is_array($document) || (is_array($document) && isset($document['token']))) {
                $document = [$document];
            } 
            $this->getMapper()->delete($m_message_doc);
            $m_message_doc->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
            foreach ($document as $d) {
                
                $token = (isset($d['token'])) ? $d['token']:$d;
                $name = (isset($d['name'])) ? $d['name']:null;  
                $type = (isset($d['type'])) ? $d['type']:null;
                
                $library_id = $this->getServiceLibrary()->add($name,null,$token,$type,ModelLibrary::FOLDER_OTHER_INT);
                $m_message_doc->setToken($token)->setName($name)->setLibrary($library_id);
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
        return $this->getMapper()->select($this->getModel()
            ->setMessageId($message_id));
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
