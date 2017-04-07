<?php

namespace Application\Service;

use Dal\Service\AbstractService;
use Application\Model\Conversation as ModelConversation;

class Conversation extends AbstractService
{
  /**
   * Create New Conversation
   *
   * @invokable
   *
   * @param array     $users
   *
   * @throws \Exception
   *
   * @return int
   */
    public function create($users = null)
    {
      return $this->_create(ModelConversation::TYPE_CHAT, $users);
    }

    public function _create($type = ModelConversation::TYPE_CHAT, $users = null, $has_video = null)
    {
      $m_conversation = $this->getModel()
          ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'))
          ->setType($type);

      if ($this->getMapper()->insert($m_conversation) <= 0) {
          throw new \Exception('Error create conversation');
      }

      $conversation_id = $this->getMapper()->getLastInsertValue();

      if ($has_video === true) {
          $this->addVideo($conversation_id);
      }
      if (null !== $users) {
          $this->getServiceConversationUser()->add($conversation_id, $users);
      }

      return $conversation_id;
  }

  /**
   * Get Conversation
   *
   * @invokable
   *
   * @param int|array
   */
  public function get($id)
  {
      $user_id = $this->getServiceUser()->getIdentity()['id'];

      $res_conversation = $this->getMapper()->getList($user_id, $id);
      foreach ($res_conversation as $m_conversation) {
        $m_conversation->setUsers($this->getServiceConversationUser()->getListUserIdByConversation($m_conversation->getId()));
      }
      $res_conversation->rewind();

      return (is_array($id)) ?
        $res_conversation->toArray(['id']) :
        $res_conversation->current();
  }

  /**
   * Get Conversation Unread
   *
   * @invokable
   *
   */
  public function getListId($contact = null, $noread = null, $type = null, $filter = null)
  {
      $user_id = $this->getServiceUser()->getIdentity()['id'];

      $mapper = $this->getMapper();
      $res_conversation = $mapper->usePaginator($filter)->getId($user_id, $contact, $noread, $type);
      foreach ($res_conversation as $m_conversation) {
        $m_conversation->setUsers($this->getServiceConversationUser()->getListUserIdByConversation($m_conversation->getId()));
      }

      $res_conversation->rewind();

      return (null === $filter) ? $res_conversation : [
        'list' => $res_conversation,
        'count' => $mapper->count()
      ];
  }

  /**
   * Add video Token in conversaton if not exist.
   *
   * @param int $id
   *
   * @return int
   */
  public function addVideo($id)
  {
      $m_conversation = $this->getMapper()->select(
        $this->getModel()->setId($id))->current();

      $token = $m_conversation->getToken();
      $media_mode = $m_conversation->getType() === ModelConversation::TYPE_CHAT ? MediaMode::RELAYED : MediaMode::ROUTED;

      return ($token === null || $token instanceof IsNull) ?
          $this->getMapper()->update(
              $this->getModel()->setToken(
                  $this->getServiceZOpenTok()
                      ->getSessionId($media_mode)
              ), ['id' => $id, new IsNull('token')]
          ) : 0;
  }

  /**
   * Get Id conversation By user(s)
   *
   * @invokable
   *
   * @param int|array $user
   *
   * @return int
   */
  public function getIdByUser($user_id)
  {
      if(!is_array($user_id)) {
        $user_id = [$user_id];
      }

      return $this->getServiceConversationUser()->getConversationIDByUser($user_id);
  }

  /**
  * Get Conversation
  *
  * @return \Application\Model\Conversation
  */
  public function getLite($id)
  {
     $res_conversation = $this->getMapper()->select($this->getModel()->setId($id));

     return (is_array($id)) ?
      $res_conversation :
      $res_conversation->current();
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
   * Get Service User.
   *
   * @return \Application\Service\User
   */
  private function getServiceUser()
  {
      return $this->container->get('app_service_user');
  }
}
