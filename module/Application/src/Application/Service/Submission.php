<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class Submission extends AbstractService
{

  /**
  * Get or Create Submision
  *
  * @param int $item_id
  * @param int $user_id
  */
  public function getOrCreate($item_id, $user_id = null)
  {
      $me = $this->getServiceUser()->getIdentity()['id'];
      $m_item = $this->getServiceItem()->getLite($item_id)->current();
      $page_id = $m_item->getPageId();
      $ar_p = $this->getServicePageUser()->getListByPage($page_id);
      $ar_pa = $this->getServicePageUser()->getListByPage($page_id, 'admin');
      $ar_pu = $this->getServicePageUser()->getListByPage($page_id, 'user');

      //si la personne ne fait pas partie du cour
      if(!in_array($me, $ar_p[$page_id])) {
         throw new \Exception("Error Processing Request", 1);
      }

      $is_admin = in_array($me, $ar_pa[$page_id]);
      if(null === $user_id || !$is_admin){
        $user_id = $me;
      }

      //si le user_id final n'est pas un student du cour
      if(!in_array($user_id, $ar_pu[$page_id])) {
         throw new \Exception("Error Processing Request", 1);
      }

      switch($m_item->getParticipants()) {
          case 'all' :
              $res_submission = $this->getMapper()->get(null, $item_id, $user_id);
              if($res_submission->count() <= 0) {
                $this->getMapper()->insert($this->getModel()->setItemId($item_id));
                $submission_id  = (int) $this->getMapper()->getLastInsertValue();
                $m_item_user = $this->getServiceItemUser()->getOrCreate($user_id, $item_id, $submission_id);
                $m_submission = $this->getMapper()->get(null, $item_id, $user_id)->current();
              }
              else{
                $m_submission = $res_submission->current();
              }

          break;
      }

      $m_submission->setItemUsers($this->getServiceItemUser()->getList($item_id, null, $m_submission->getId()));
      if(!is_numeric($m_submission->getPostId())) {
          $post_id = $this->getServicePost()->add(null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,'submission');
          $m_submission->setPostId($post_id);
          $this->getMapper()->update($m_submission);
      }

      return $m_submission;
  }

  /**
  * Get Post_id submission
  *
  * @invokable
  *
  * @param int $item_id
  * @param int $user_id
  *
  **/
  public function getPostId($item_id, $user_id = null)
  {
    return $this->getOrCreate($item_id, $user_id)->getPostId();
  }

  /**
  * Add Submision
  *
  * @invokable
  *
  * @param int $item_id
  * @param int $library_id
  */
  public function add($item_id, $library_id)
  {
      $page_id = $this->getServiceItem()->getLite($item_id)->current()->getPageId();
      $ar_pu = $this->getServicePageUser()->getListByPage($page_id, 'user');
      $identity = $this->getServiceUser()->getIdentity();

      if(!in_array($identity['id'], $ar_pu[$page_id])) {
        throw new \Exception("No User", 1);
      }

      $m_submission = $this->getOrCreate($item_id, $identity['id']);

      $submission_id = $m_submission->getId();
      $this->getServiceSubmissionLibrary()->add($submission_id, $library_id);

      return $submission_id;
  }

  /**
  * REMOVE Submision
  *
  * @invokable
  *
  * @param int $library_id
  * @param int $id
  */
  public function remove($library_id, $id = null)
  {
    if(null === $id){
        $res_submission_library = $this->getServiceSubmissionLibrary()->getList(null, $library_id);
        if($res_submission_library->count() <= 0) {
          throw new \Exception("Error Processing Request", 1);
        }
        $id = $res_submission_library->current()->getSubmissionId();
    }

    $identity = $this->getServiceUser()->getIdentity();

    $res_item_user = $this->getServiceItemUser()->getLite(null, $identity['id'] , $id);
    if($res_item_user->count() <= 0) {
      throw new \Exception("Bad User", 1);
    }

    return $this->getServiceSubmissionLibrary()->remove($id, $library_id);
  }

  /**
  * Submit Submision
  *
  * @invokable
  *
  * @param int $id
  */
  public function submit($id = null, $item_id = null)
  {
    $identity = $this->getServiceUser()->getIdentity();

    if(null === $id && null !== $item_id) {
      $id = $this->getOrCreate($item_id)->getId();
    }

    if(!$id && $this->getServiceItemUser()->getLite(null,$identity['id'], $id)->count() <= 0) {
      throw new \Exception("Bad User", 1);
    }

    return $this->getMapper()->update($this->getModel()->setId($id)->setSubmitDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s')));
  }

  /**
  * Get Library Submision
  *
  * @invokable
  *
  * @param int $item_id
  * @param int $user_id
  * @param int $group_id
  */
  public function getListLibrary($item_id, $user_id = null, $group_id = null)
  {
    $ar = [];
    if(is_array($item_id)) {
      $item_id = reset($item_id);
    }

    $identity = $this->getServiceUser()->getIdentity();

    $page_id = $this->getServiceItem()->getLite($item_id)->current()->getPageId();
    $ar_pu = $this->getServicePageUser()->getListByPage($page_id, 'admin');
    $is_admin = (in_array($identity['id'], $ar_pu[$page_id]));
    if(!$is_admin) {
      $ar[$item_id] = [];
    }
    if(null === $user_id && $group_id === null && !$is_admin) {
      $user_id = $identity['id'];
    }
    $res_item_user = $this->getServiceItemUser()->getLite(null, $user_id, null, $group_id, $item_id);
    if($res_item_user->count() > 0) {
      $res_submission_library = $this->getServiceSubmissionLibrary()->getList($res_item_user->current()->getSubmissionId());
      foreach ($res_submission_library as $m_submission_library) {
        if($is_admin) {
          $ar[] = $m_submission_library->getLibraryId();
        } else {
          $ar[$res_item_user->current()->getItemId()][] = $m_submission_library->getLibraryId();
        }
      }
    }

    return $ar;
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
   * Get Service Item
   *
   * @return \Application\Service\Item
   */
  private function getServiceItem()
  {
      return $this->container->get('app_service_item');
  }

  /**
   * Get Service Item User
   *
   * @return \Application\Service\ItemUser
   */
  private function getServiceItemUser()
  {
      return $this->container->get('app_service_item_user');
  }

  /**
   * Get Service Submission Library
   *
   * @return \Application\Service\SubmissionLibrary
   */
  private function getServiceSubmissionLibrary()
  {
      return $this->container->get('app_service_submission_library');
  }

  /**
   * Get Service User
   *
   * @return \Application\Service\User
   */
  private function getServiceUser()
  {
      return $this->container->get('app_service_user');
  }

  /**
   * Get Service Post
   *
   * @return \Application\Service\Post
   */
  private function getServicePost()
  {
      return $this->container->get('app_service_post');
  }
}
