<?php

namespace Application\Service;

use Dal\Service\AbstractService;
use Zend\Db\Sql\Predicate\IsNull;
use ZendService\Google\Gcm\Notification as GcmNotification;

class MessageUser extends AbstractService
{

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

          ////////////////////// DOCUMENT /////////////////////////////
          $docs = [];

          if ($me != $user) {
              $gcm_notification = new GcmNotification();
              $tmp_ar_name = $ar_name;
              unset($tmp_ar_name[$user]);
              $gcm_notification->setTitle(implode(", ", $tmp_ar_name))
                  ->setSound("default")
                  ->setColor("#00A38B")
                  ->setIcon("icon")
                  ->setTag("CONV".$conversation_id)
                  ->setBody(((count($to) > 2)? explode(' ', $ar_name[$me])[0] . ": ":"").(empty($message_text)?"shared a file.":$message_text));

              $this->getServiceFcm()->send(
                  $user, ['data' => [
                      'type' => 'message',
                      'data' => ['users' => $to,
                          'from' => $me,
                          'conversation' => $conversation_id,
                          'text' => $message_text,
                          'doc' => 'document'
                      ],
                  ]], $gcm_notification
              );
          }

      }

      return $this->getMapper()->getLastInsertValue();
  }

  /**
   * Mark read Message User by conversation.
   *
   * @param int|array $conversation_id
   *
   * @return int
   */
  public function readByConversation($conversation_id)
  {
      $user_id = $this->getServiceUser()->getIdentity()['id'];

      if (!is_array($conversation_id)) {
          $conversation_id = [$conversation_id];
      }

      $m_message_user = $this->getModel()->setReadDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));

      return $this->getMapper()->update($m_message_user, ['conversation_id' => $conversation_id, 'user_id' => $user_id, new IsNull('read_date')]);
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
