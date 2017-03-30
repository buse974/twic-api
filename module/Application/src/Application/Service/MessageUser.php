<?php

namespace Application\Service;

use Dal\Service\AbstractService;
use Application\Model\Conversation as ModelConversation;
use Zend\Json\Server\Request;
use Zend\Http\Client;
use ZendService\Google\Gcm\Notification as GcmNotification;

class MessageUser extends AbstractService
{
  private static $id = 0;

  /**
   * Send message.
   *
   * @param int      $message_id
   * @param int      $conversation_id
   * @param string   $message_text
   * @param string   $message_token
   *
   * @throws \Exception
   *
   * @return int
   */
  public function send($message_id, $conversation_id, $message_text, $message_token)
  {
      $me = $this->getServiceUser()->getIdentity()['id'];

      $for_me = false;
      $to = $this->getServiceConversationUser()->getListUserIdByConversation($conversation_id);

      $date = (new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s');
      foreach ($to as $user) {
          $m_message_user = $this->getModel()
              ->setMessageId($message_id)
              ->setConversationId($conversation_id)
              ->setFromId($me)
              ->setUserId($user)
              ->setType((($user == $me) ? (($for_me) ? 'RS' : 'S') : 'R'))
              ->setCreatedDate($date);

          if ($me == $user && !$for_me) {
              $m_message_user->setReadDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
          }

          if ($this->getMapper()->insert($m_message_user) <= 0) {
              throw new \Exception('error insert message to');
          }
      }

      //////////////////// USER //////////////////////////////////
      $res_user = $this->getServiceUser()->getLite($to);
      $ar_name = [];
      foreach ($res_user as $m_user) {
          $name = "";
          if (!is_object($m_user->getNickname()) &&  null !== $m_user->getNickname()) {
              $name = $m_user->getNickname();
          } else {
              if (!is_object($m_user->getFirstname()) &&  null !== $m_user->getFirstname()) {
                  $name = $m_user->getFirstname();
              }
              if (!is_object($m_user->getLastname()) &&  null !== $m_user->getLastname()) {
                  $name .= ' '.$m_user->getLastname();
              }
          }
          $ar_name[$m_user->getId()] = $name;
      }

      //////////////////////// NODEJS //////////////////////////////:
      $this->sendMessage(
          [
          'content' => $message_text,
          'cid' => (int)$conversation_id,
          'token' => $message_token,
          'mid' => (int)$message_id,
          'from' => (int)$me,
          'users' => $to,
          'created_date' => date('c'),
          'type' => 2,
          ]
      );

      ///////////////////////// FCM /////////////////////////////////
      foreach ($to as $user) {
          if ($me != $user) {
              $gcm_notification = new GcmNotification();
              $tmp_ar_name = $ar_name;
              unset($tmp_ar_name[$user]);
              $gcm_notification->setTitle(implode(", ", $tmp_ar_name))
                  ->setSound("default")
                  ->setColor("#00A38B")
                  ->setTag("CONV".$conversation_id)
                  ->setBody(((count($to) > 2)? explode(' ', $ar_name[$me])[0] . ": ":"").(empty($message_text)?"shared ".$message_token." items.":$message_text));

              $this->getServiceFcm()->send(
                  $user, ['data' => [
                      'type' => 'message',
                      'data' => ['users' => $to,
                          'from' => $me,
                          'conversation' => $conversation_id,
                          'text' => $message_text,
                          'token' => $message_token
                      ],
                  ]], $gcm_notification
              );
          }
      }



      return $this->getMapper()->getLastInsertValue();
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
   * Get Service Service User.
   *
   * @return \Application\Service\User
   */
  private function getServiceUser()
  {
      return $this->container->get('app_service_user');
  }

  /**
   * Get Service Service Conversation User.
   *
   * @return \Application\Service\ConversationUser
   */
  private function getServiceConversationUser()
  {
      return $this->container->get('app_service_conversation_user');
  }

  /**
   * Get Service Service Conversation User.
   *
   * @return \Application\Service\Fcm
   */
  private function getServiceFcm()
  {
      return $this->container->get('fcm');
  }
}
