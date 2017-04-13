<?php

namespace Application\Service;

use Dal\Service\AbstractService;
use Application\Model\Conversation as ModelConversation;
use Zend\Json\Server\Request;
use Zend\Http\Client;

class Message extends AbstractService
{
  private static $id = 0;
  
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

      if (empty($text) && empty($library)) {
          throw new \Exception('error content && document are empty');
      }

      $library_id = (is_array($library)) ? $this->getServiceLibrary()->_add($library)->getId() : null;

      $m_message = $this->getModel()
          ->setText($text)
          ->setLibraryId($library_id)
          ->setUserId($user_id)
          ->setConversationId($conversation_id)
          ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));

      if ($this->getMapper()->insert($m_message) <= 0) {
          throw new \Exception('error insert message');
      }

      $id = $this->getMapper()->getLastInsertValue();

      $type = $this->getServiceConversation()->getLite($conversation_id)->getType();
      if($type === ModelConversation::TYPE_CHAT) {
        $message_user_id = $this->getServiceMessageUser()->send($id, $conversation_id, $text, $library);
      }

      $to = $this->getServiceConversationUser()->getListUserIdByConversation($conversation_id);
      //////////////////////// NODEJS //////////////////////////////:
      $this->sendMessage([
          'conversation_id' => (int)$conversation_id,
          'id' => (int)$id,
          'users' => $to,
          'type' => $type,
        ]
      );

      return [
        'message_id' => $id,
        'conversation_id' => $conversation_id
      ];
  }

  /**
  * Send Message Node message.publish
  *
  * @param string $data
  */
  public function sendMessage($data)
  {
      $rep = false;
      $request = new Request();
      $request->setMethod('message.publish')
          ->setParams($data)
          ->setId(++ self::$id)
          ->setVersion('2.0');

      $client = new Client();
      $client->setOptions($this->container->get('config')['http-adapter']);

      $client = new \Zend\Json\Server\Client($this->container->get('config')['node']['addr'], $client);
      try {
          $rep = $client->doRequest($request);
          if ($rep->isError()) {
              throw new \Exception('Error jrpc nodeJs: ' . $rep->getError()->getMessage(), $rep->getError()->getCode());
          }
      } catch (\Exception $e) {
          syslog(1, 'Request: ' . $request->toJson());
          syslog(1, $e->getMessage());
      }

      return $rep;
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
