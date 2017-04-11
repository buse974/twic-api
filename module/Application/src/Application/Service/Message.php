<?php

namespace Application\Service;

use Dal\Service\AbstractService;
use Application\Model\Conversation as ModelConversation;

class Message extends AbstractService
{
  /**
   * Send message generique.
   *
   * @invokable
   *
   * @param string    $text
   * @param string    $library
   * @param int|array $to
   * @param int       $conversation_id
   *
   * @throws \Exception
   *
   * @return \Application\Model\MessageUser
   */
  public function send($text = null, $library = null, $to = null, $conversation_id = null)
  {
      $user_id = $this->getServiceUser()->getIdentity()['id'];

      if (null !== $to && $conversation_id === null) {
          if (!is_array($to)) {
              $to = [$to];
          }
          if (!in_array($user_id, $to)) {
              $to[] = $user_id;
          }
          $conversation_id = $this->getServiceConversationUser()->getConversationIDByUser($to);
          if($conversation_id === false) {
            $conversation_id = $this->getServiceConversation()->_create(ModelConversation::TYPE_CHAT, $to);
          }
      } elseif ($conversation_id !== null) {
          if (!$this->getServiceConversationUser()->isInConversation($conversation_id, $user_id)) {
              throw new \Exception('User '.$user_id.' is not in conversation '.$conversation_id);
          }
      }

      if (empty($text) && empty($document)) {
          throw new \Exception('error content && document are empty');
      }

      $library_id = (is_array($library)) ? $this->getServiceLibrary()->_add($library)->getId() : null;

      $m_message = $this->getModel()
          ->setText($text)
          ->setLibraryId($library_id)
          ->setConversationId($conversation_id)
          ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));

      if ($this->getMapper()->insert($m_message) <= 0) {
          throw new \Exception('error insert message');
      }

      $id = $this->getMapper()->getLastInsertValue();
      if($this->getServiceConversation()->getLite($conversation_id)->getType() === ModelConversation::TYPE_CHAT) {
        $message_user_id = $this->getServiceMessageUser()->send($id, $conversation_id, $text, $library);
      }

      return [
        'message_id' => $id,
        'conversation_id' => $conversation_id
      ];
  }

  /**
   * Get List By user Conversation
   *
   * @invokable
   *
   * @param int   $conversation_id
   * @param array $filter
   */
  public function getList($conversation_id, $filter = [])
  {
      $user_id = $this->getServiceUser()->getIdentity()['id'];
      $mapper = $this->getMapper();
      $res_message = $mapper->usePaginator($filter)->getList($user_id, $conversation_id);

      return [
        'list' => $res_message,
        'count' => $mapper->count()
      ];
  }

  /**
   * Get Message Doc.
   *
   * @return \Application\Service\MessageDoc
   */
  private function getServiceMessageDoc()
  {
      return $this->container->get('app_service_message_doc');
  }

  /**
   * Get Service User.
   *
   * @return \Application\Service\User
   */
  private function getServiceUser()
  {
      return $this->container->get('app_service_user');
  }

  /**
   * Get Service ConversationUser.
   *
   * @return \Application\Service\ConversationUser
   */
  private function getServiceConversationUser()
  {
      return $this->container->get('app_service_conversation_user');
  }

  /**
   * Get Service Message User
   *
   * @return \Application\Service\MessageUser
   */
  private function getServiceMessageUser()
  {
      return $this->container->get('app_service_message_user');
  }

  /**
   * Get Service Conversation
   *
   * @return \Application\Service\Conversation
   */
  private function getServiceConversation()
  {
      return $this->container->get('app_service_conversation');
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
