<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Message Document 
 */
namespace Application\Service;

use Dal\Service\AbstractService;

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
            if (!is_array($document)) {
                $document = [$document];
            }
            $this->getMapper()->delete($m_message_doc);

            $m_message_doc->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
            foreach ($document as $d) {
                $m_message_doc->setToken($d['token'])->setName($d['name']);

                $ret[$d['token']] = $this->getMapper()->insert($m_message_doc);
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
}
