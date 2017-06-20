<?php

namespace Application\Service;

use Dal\Service\AbstractService;
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

    public function _create($type = ModelConversation::TYPE_CHAT, $users = null, $has_video = null, $name = null)
    {
      $m_conversation = $this->getModel()
          ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'))
          ->setName($name)
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

  public function update($id, $name)
  {
    $m_conversation = $this->getModel()
        ->setId($id)
        ->setName($name);

    return $this->getMapper()->update($m_conversation);
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

      $res_conversation = $this->getMapper()->getId($user_id, null, null, null, null, $id);
      foreach ($res_conversation as $m_conversation) {
        $message_id = $m_conversation->getMessage()->getId();
        if(is_numeric($message_id)) {
          $m_conversation->setMessage($this->getServiceMessage()->get($message_id));
        }

        if($m_conversation->getType() !== ModelConversation::TYPE_CHANNEL) {
          $m_conversation->setUsers($this->getServiceConversationUser()->getListUserIdByConversation($m_conversation->getId()));
        }

        $m_page = $this->getServicePage()->getByConversationId($id);
        if($m_page) {
          $role = $this->getServicePageUser()->getRole($m_page->getId());
          if($role) {
            $m_conversation->setRole($role->getRole());
          }
        }
        //TYPE 2 => CHAT   ::: TYPE 1 => CHANNEL
        if($m_conversation->getType() === ModelConversation::TYPE_CHAT) {
          if($user_id === 7 || $user_id === 3) {
            $m_conversation->setOptions([
              "record" => true,
              "nb_user_autorecord" => 2,
              "rules" => [
              "autoPublishCamera"     => true,
              "autoPublishMicrophone" => true,
              "archive"               => true,
              "raiseHand"             => false,
              "publish"               => true,
              "askDevice"             => true,
              "askScreen"             => true,
              "forceMute"             => true,
              "forceUnpublish"        => true,
              "kick"                  => true ]
            ]);
          } else {
            $m_conversation->setOptions([
              "record" => true,
              "nb_user_autorecord" => 2,
              "rules" => [
              "autoPublishCamera"     => true,
              "autoPublishMicrophone" => false,
              "archive"               => false,
              "raiseHand"             => false,
              "publish"               => true,
              "askDevice"             => false,
              "askScreen"             => false,
              "forceMute"             => false,
              "forceUnpublish"        => false,
              "kick"                  => false ]
            ]);
          }
        } else if($m_conversation->getType() === ModelConversation::TYPE_CHANNEL) {
          $m_conversation->setOptions([
            "record" => true,
             "nb_user_autorecord" => 2,
             "rules" => [
            "autoPublishCamera"       => [["roles" => ["admin"]]],
            "autoPublishMicrophone"   => false,
            "archive"                 => [["roles" => ["admin"]]],
            "raiseHand"               => [["roles" => ["user"]]],
            "publish"                 => [["roles" => ["admin"]]],
            "askDevice"               => [["roles" => ["admin"]]],
            "askScreen"               => [["roles" => ["admin"]]],
            "forceMute"               => [["roles" => ["admin"]]],
            "forceUnpublish"          => [["roles" => ["admin"]]],
            "kick"                    => [["roles" => ["admin"]]],
            ]
          ]);
        }

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
    $this->getServicePage()->addChannel();

      $user_id = $this->getServiceUser()->getIdentity()['id'];

      $mapper = $this->getMapper();
      $res_conversation = $mapper->usePaginator($filter)->getId($user_id, $contact, $noread, $type, $search);
      foreach ($res_conversation as $m_conversation) {
        if($m_conversation->getType() !==  ModelConversation::TYPE_CHANNEL) {
          $m_conversation->setUsers($this->getServiceConversationUser()->getListUserIdByConversation($m_conversation->getId()));
        }
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
        'session' => $token,
        'role' => ($user_id == 3 || $user_id == 7 ) ? 'admin':'user'
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
   * Mark Read Message(s).
   *
   * @invokable
   *
   * @param int|array $id
   *
   * @return int
   */
  public function read($id)
  {
      return $this->getServiceConversationUser()->read($id);
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
   * Get Service Messsage.
   *
   * @return \Application\Service\Message
   */
  private function getServiceMessage()
  {
      return $this->container->get('app_service_message');
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

  /**
   * Get Service Page User
   *
   * @return \Application\Service\PageUser
   */
  private function getServicePageUser()
  {
      return $this->container->get('app_service_page_user');
  }

  /**
   * Get Service Page
   *
   * @return \Application\Service\Page
   */
  private function getServicePage()
  {
      return $this->container->get('app_service_page');
  }
}
