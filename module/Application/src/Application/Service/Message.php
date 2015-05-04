<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class Message extends AbstractService
{
    /**
     * Send message.
     *
     * @invokable
     *
     * @param string $suject
     * @param string $content
     * @param array  $receiver
     * @param array  $documents
     * @param int    $group_id
     *
     * @throws \Exception
     *
     * @return int
     */
    public function send($suject, $content, $receiver, $documents = null, $group_id = null)
    {
        if (null === $group_id) {
            $group_id = uniqid();
        }

        if ($this->getMapper()->insert($this->getModel()
                                            ->setSuject($suject)
                                            ->setContent($content)
                                            ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'))
                                            ->setMessageGroupId($group_id)
                                            ->setDraft(false)) <= 0) {
            throw new \Exception('error insert messge');
        }

        $message_id = $this->getMapper()->getLastInsertValue();

        $receiver[] = array('type' => 'from', 'user' => $this->getServiceAuth()->getIdentity()->getId());

        foreach ($receiver as $mr) {
            $this->getServiceMessageUser()->add($message_id, $mr['user'], $group_id);
        }
        $this->getServiceMessagereceiver()->add($message_id, $receiver);

        if (null !== $documents) {
            foreach ($documents as $doc) {
                $this->getServiceMessageDoc()->add($message_id, $doc);
            }
        }

        return array('message' => $message_id, 'group' => $group_id);
    }

    /**
     * Add receiver.
     *
     * @invokable
     *
     * @param int   $message
     * @param array $receiver
     */
    public function addReceiver($message, $receiver)
    {
        // @TODO test update only draft
        return $this->getServiceMessagereceiver()->_add($receiver['type'], $receiver['user'], $message);
    }

    /**
     * Delete receiver.
     *
     * @invokable
     *
     * @param int $message
     * @param int $receiver_id
     */
    public function deleteReceiver($message, $receiver_id)
    {
        // @TODO test update only draft
        return $this->getServiceMessagereceiver()->delete($receiver_id, $message);
    }

    /**
     * Add Document.
     *
     * @invokable
     *
     * @param int    $message
     * @param string $document
     */
    public function addDocument($message, $document)
    {
        return $this->getServiceMessageDoc()->add($message, $document);
    }

    /**
     * Delete Document by Id.
     *
     * @invokable
     *
     * @param int $message
     * @param int $document_id
     */
    public function deleteDocument($message, $document_id)
    {
        return $this->getServiceMessageDoc()->delete($message, $document_id);
    }

    /**
     * Save messge by draft.
     *
     * @invokable
     *
     * @param strung $suject
     * @param string $content
     * @param array  $receiver
     * @param string $documents
     * @param string $group_id
     *
     * @throws \Exception
     *
     * @return int
     */
    public function save($suject, $content, $receiver, $documents = null, $group_id = null)
    {
        if (null === $group_id) {
            $group_id = uniqid();
        }

        if ($this->getMapper()->insert($this->getModel()
                ->setSuject($suject)
                ->setContent($content)
                ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'))
                ->setMessageGroupId($group_id)
                ->setDraft(true)) <= 0) {
            throw new \Exception('error insert draft');
        }

        $message_id = $this->getMapper()->getLastInsertValue();

        $receiver[] = array('type' => 'from', 'user' => $this->getServiceAuth()->getIdentity()->getId());

        if (null !== $receiver) {
            $this->getServiceMessagereceiver()->add($message_id, $receiver);
        }

        if (null !== $documents) {
            foreach ($documents as $doc) {
                $this->getServiceMessageDoc()->add($message_id, $doc);
            }
        }

        return array('message' => $message_id, 'group' => $group_id);
    }

    /**
     * Update message.
     *
     * @invokable
     *
     * @param int    $id
     * @param string $suject
     * @param string $content
     * @param array  $receiver
     * @param array  $documents
     *
     * @return int
     */
    public function update($id, $suject = null, $content = null, $receiver = null, $documents = null)
    {
        $m_message = $this->getModel()->setId($id);

        if (null !== $suject) {
            $m_message->setSuject($suject);
        }
        if (null !== $content) {
            $m_message->setContent($content);
        }
        if (null !== $receiver) {
            $receiver[] = array('type' => 'from', 'user' => $this->getServiceAuth()->getIdentity()->getId());
            $this->getServiceMessagereceiver()->replace($receiver, $id);
        }
        if (null !== $documents) {
            $this->getServiceMessageDoc()->replace($documents, $id);
        }

        return $this->getMapper()->update($m_message);
    }

    /**
     * Send message Draft.
     *
     * @invokable
     *
     * @param int $id
     *
     * @return int
     */
    public function sendById($id)
    {
        //@TODO check from is userid
        $group_id = $this->getMapper()->select($this->getModel()->setId($id))->current()->getMessageGroupId();
        $receiver = $this->getServiceMessagereceiver()->getByMessage($id);

        foreach ($receiver as $mr) {
            $this->getServiceMessageUser()->add($id, $mr->getUserId(), $group_id);
        }

        return $this->getMapper()->update($this->getModel()->setId($id)->setDraft(false));
    }

    /**
     * Get list preview.
     *
     * @invokable
     *
     * @param string $tag
     * @param array  $filter
     *
     * @return array
     */
    public function getListPreview($tag = null, $filter = array())
    {
        $mapper = $this->getMapper();
        $res_message = $mapper->usePaginator($filter)->getListPreview($this->getServiceAuth()->getIdentity()->getId(), $tag);

        foreach ($res_message as $mess) {
            $mess->setReceiver($this->getServiceMessagereceiver()->getByMessage($mess->getId()));
        }

        return array('count' => $mapper->count(), 'list' => $res_message);
    }

    /**
     * Get List Message By Group.
     *
     * @invokable
     *
     * @param int   $group
     * @param array $filter
     */
    public function getListByGroup($group, $filter = array())
    {
        $mapper = $this->getMapper();
        $res_message = $mapper->usePaginator($filter)->getListByGroup($this->getServiceAuth()->getIdentity()->getId(), $group);

        foreach ($res_message as $mess) {
            $mess->setReceiver($this->getServiceMessagereceiver()->getByMessage($mess->getId()));
            $mess->setMessageDocument($this->getServiceMessageDoc()->getByMessage($mess->getId()));
        }

        return array('count' => $mapper->count(), 'list' => $res_message);
    }

    /**
     * Delete Message.
     *
     * @invokable
     *
     * @param int $id
     */
    public function delete($id)
    {
        return $this->getServiceMessageUser()->delete($this->getServiceAuth()->getIdentity()->getId(), $id);
    }

    /**
     * @return \Application\Service\MessageGroup
     */
    public function getServiceMessageGroup()
    {
        return $this->getServiceLocator()->get('app_service_message_group');
    }

    /**
     * @return \Application\Service\MessageUser
     */
    public function getServiceMessageUser()
    {
        return $this->getServiceLocator()->get('app_service_message_user');
    }

    /**
     * @return \Application\Service\Messagereceiver
     */
    public function getServiceMessagereceiver()
    {
        return $this->getServiceLocator()->get('app_service_message_receiver');
    }

    /**
     * @return \Application\Service\MessageDoc
     */
    public function getServiceMessageDoc()
    {
        return $this->getServiceLocator()->get('app_service_message_doc');
    }

    /**
     * @return \Auth\Service\AuthService
     */
    public function getServiceAuth()
    {
        return $this->getServiceLocator()->get('auth.service');
    }
}
