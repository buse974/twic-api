<?php

namespace Application\Service;

use Dal\Service\AbstractService;
use Application\Model\Library;
use Application\Model\Conversation as ModelConversation;
use OpenTok\MediaMode;
use OpenTok\Role as OpenTokRole;
use Zend\Db\Sql\Predicate\IsNull;

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
   * @param bool $contact
   * @param bool $noread
   * @param int $type
   * @param array $filter
   * @param string $search
   */
  public function getList($contact = null, $noread = null, $type = null, $filter = null, $search = null)
  {
      $user_id = $this->getServiceUser()->getIdentity()['id'];

      $mapper = $this->getMapper();
      $res_conversation = $mapper->usePaginator($filter)->getId($user_id, $contact, $noread, $type, $search);
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
   * @invokable
   *
   * @param int $id
   *
   * @return string
   */
  public function addVideo($id)
  {
      $m_conversation = $this->getMapper()->select($this->getModel()->setId($id))->current();
      $token = $m_conversation->getToken();
      $media_mode = ($m_conversation->getType() === ModelConversation::TYPE_CHAT) ?
        MediaMode::RELAYED :
        MediaMode::ROUTED;

      if ($token === null || $token instanceof IsNull) {
        $token = $this->getServiceZOpenTok()->getSessionId($media_mode);
        $this->getMapper()->update($this->getModel()->setToken($token), ['id' => $id, new IsNull('token')]);
      }

      return $token;
  }

  /**
   * Get Token video Token User in conversaton if not exist.
   *
   * @invokable
   *
   * @param int $id
   *
   * @return int
   */
  public function getToken($id)
  {
      $user_id = $this->getServiceUser()->getIdentity()['id'];
      $token = $this->addVideo($id);

      return [
        'token' => $this->getServiceZOpenTok()->createToken($token,'{"id":' . $user_id . '}', OpenTokRole::MODERATOR/* : OpenTokRole::PUBLISHER*/),
        'session' => $token
      ];
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

  /**
   * Get Service Service OpenTok.
   *
   * @return \ZOpenTok\Service\OpenTok
   */
  private function getServiceZOpenTok()
  {
      return $this->container->get('opentok.service');
  }
}
