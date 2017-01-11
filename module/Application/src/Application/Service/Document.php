<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Document
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class Document.
 */
class Document extends AbstractService
{
    /**
     * Add Document.
     *
     * @param string $name
     * @param string $type
     * @param string $link
     * @param string $token
     * @param string $item_id
     * @param int    $submission_id
     * @param int    $folder_id
     * @param int    $conversation_id
     *
     * @throws \Exception
     *
     * @return int
     */
    public function add($name = null, $type = null, $link = null, $token = null, $item_id = null, $submission_id = null, $folder_id = null, $conversation_id = null)
    {
        if (null === $link && null === $token && null === $name) {
            return 0;
        }
        if (null !== $submission_id) {
            $item_id = null;
        }

        if (null !== $item_id) {
            $this->getMapper()->delete(
                $this->getModel()
                    ->setItemId($item_id)
            );
        }

        $library_id = $this->getServiceLibrary()
            ->add($name, $link, $token, $type, $folder_id)
            ->getId();
        $m_document = $this->getModel()
            ->setItemId($item_id)
            ->setSubmissionId($submission_id)
            ->setLibraryId($library_id)
            ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));

        if ($this->getMapper()->insert($m_document) <= 0) {
            throw new \Exception('error insert document relation');
        }

        if (null !== $conversation_id) {
            $this->getServiceConversationDoc()->add($conversation_id, $library_id);
        }

        return $this->getServiceLibrary()->get($library_id);
    }

    /**
     * Add Relation.
     *
     * @param int $submission_id
     * @param int $library_id
     *
     * @throws \Exception
     *
     * @return int
     */
    public function addRelation($submission_id, $library_id)
    {
        $m_document = $this->getModel()
            ->setSubmissionId($submission_id)
            ->setLibraryId($library_id);

        $res_document = $this->getMapper()->select($m_document);

        return ($res_document->count() == 0) ? $this->getMapper()->insert($m_document->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'))) : 0;
    }

    /**
     * Add Document in Conversation.
     *
     * @param array $data
     *
     * @return int
     */
    public function addConversation($data)
    {
        return $this->add($data['name'], $data['type'], $data['link'], $data['token'], $data['item_id'], $data['submission_id'], $data['folder_id'], $data['conversation_id']);
    }

    /**
     * Get List By Submission.
     *
     * @param int $submission_id
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListBySubmission($submission_id)
    {
        $m_document = $this->getModel()->setSubmissionId($submission_id);

        return $this->getMapper()->select($m_document);
    }

    /**
     * Get List By Item.
     *
     * @param int $item_id
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListByItem($item_id)
    {
        return $this->getMapper()->select(
            $this->getModel()
                ->setItemId($item_id)
        );
    }

    /**
     * Delete Document.
     *
     * @invokable
     *
     * @param int $id
     * @param int $submission_id
     * @param int $library_id
     *
     * @return int
     */
    public function delete($id = null, $submission_id = null, $library_id = null)
    {
        if ($id === null && ($submission_id === null || $library_id === null)) {
            return false;
        }

        return $this->getMapper()->delete(
            $this->getModel()
                ->setId($id)
                ->setSubmissionId($submission_id)
                ->setLibraryId($library_id)
        );
    }

    /**
     * Get Service Library.
     *
     * @return \Application\Service\Library
     */
    private function getServiceLibrary()
    {
        return $this->container->get('app_service_library');
    }

    /**
     * Get Service ConversationDoc.
     *
     * @return \Application\Service\ConversationDoc
     */
    private function getServiceConversationDoc()
    {
        return $this->container->get('app_service_conversation_doc');
    }
}
