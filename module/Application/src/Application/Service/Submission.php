<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class Submission extends AbstractService
{
    
  
  /**
  * Get Submision
  *
  * @invokable
  *
   *@param int $submission_id
  * @param int $item_id
  * @param int $user_id
  */
  public function get($id = null, $item_id = null, $user_id = null, $group_id = null)
  {
        $get_all = $user_id === null;
        $me = $this->getServiceUser()->getIdentity()['id'];
        if(null !== $id){
            $m_submission = $this->getMapper()->select($this->getModel()->setId($id))->current();
        }
        else if(null !== $item_id){
            if(null === $user_id){
                $user_id = $me;
            }
            $m_item = $this->getServiceItem()->getLite($item_id)->current();
            $page_id = $m_item->getPageId();
            switch($m_item->getParticipants()){
                case 'all' : 
                    $ar_pu = $this->getServicePageUser()->getListByPage($page_id, 'user');
                    $ar_pa = $this->getServicePageUser()->getListByPage($page_id, 'admin');
                    if(!in_array($user_id, $ar_pu[$page_id]) || ($get_all && !in_array($me, $ar_pa[$page_id]))) {
                        throw new \Exception("No submission", 1);
                    }
                    
                    $res_submission = $this->getMapper()->get($item_id, $user_id);
                    if($res_submission->count() <= 0){
                        $this->getMapper()->insert($this->getModel()->setItemId($item_id));
                        $id = (int)$this->getMapper()->getLastInsertValue();
                        $m_submission = $this->getMapper()->select($this->getModel()->setId($id))->current();
                        $this->getServiceItemUser()->getOrCreate($user_id, $item_id, $id);
                    }
                    else{
                        $m_submission = $res_submission->current();
                    }
                    
                break;
            }
        }
        $m_submission->setItemUsers($this->getServiceItemUser()->getList($item_id, null, $m_submission->getId()));

     

      return $m_submission;
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

      $m_item_user = $this->getServiceItemUser()->getOrCreate($identity['id'], $item_id);

      $submission_id = $m_item_user->getSubmissionId();
      if(!is_numeric($submission_id)) {
        if($this->getMapper()->insert($this->getModel()->setItemId($item_id)) <= 0) {
          throw new \Exception("Error Processing Request", 1);
        }

        $submission_id = $id = (int)$this->getMapper()->getLastInsertValue();
        $this->getServiceItemUser()->update($m_item_user->getId(), $submission_id);
      }

      $this->getServiceSubmissionLibrary()->add($submission_id, $library_id);

      return $submission_id;
  }

  /**
  * REMOVE Submision
  *
  * @invokable
  *
  * @param int $library_id
  * @param int $submission_id
  */
  public function remove($library_id, $submission_id = null)
  {
  
    if(null === $submission_id){
        $res_submission_library = $this->getServiceSubmissionLibrary()->getList(null, $library_id);
        if($res_submission_library->count() <= 0) {
          throw new \Exception("Error Processing Request", 1);
        }
        $submission_id = $res_submission_library->current()->getSubmissionId();
    }
    
    $identity = $this->getServiceUser()->getIdentity();

    $res_item_user = $this->getServiceItemUser()->getLite(null, $identity['id'] , $submission_id);
    if($res_item_user->count() <= 0) {
      throw new \Exception("Bad User", 1);
    }

    return $this->getServiceSubmissionLibrary()->remove($submission_id, $library_id);
  }

  /**
  * Submit Submision
  *
  * @invokable
  *
  * @param int $id
  */
  public function submit($id)
  {
    $identity = $this->getServiceUser()->getIdentity();
    if($this->getServiceItemUser()->getLite(null,$identity['id'], $id)->count() <= 0) {
      throw new \Exception("Bad User", 1);
    }

    return $this->getMapper()->update($this->getModel()->setId($id)->setSubmitDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s')));
  }

  /**
  * Get Library Submision
  *
  * @invokable
  *
  * @param int $id
  */
  public function getListLibrary($id)
  {
    $res_submission_library = $this->getServiceSubmissionLibrary()->getList($id);

   $ar = [];
    foreach ($res_submission_library as $m_submission_library) {
        $ar[ $m_submission_library->getSubmissionId() ][] = $m_submission_library->getLibraryId();
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
}
