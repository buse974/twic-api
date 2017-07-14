<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class Submission extends AbstractService
{
    
  
    /**
  * Get a Submision
  *  
  * @invokable
  * 
  * @param int|array $id
  */
  public function get($id)
  {
        if(!is_array($id)){
            $id = [$id];
        }
        $submissions = [];
        foreach($id as $i){
            $submissions[$i] = null;
        }
        $res_submissions = $this->getMapper()->get($id);
        foreach($res_submissions as $m_submission){
            $m_submission->setItemUsers($this->getServiceItemUser()->getList($m_submission->getItemId(), null, $m_submission->getId()));
            $submissions[$m_submission->getId()] = $m_submission;
        }
        return $submissions;
  }
    
  /**
  * Get or Create Submision
  *  
  * 
  * @param int $id
  * @param int $item_id
  * @param int $user_id
  * @param int $group_id
  */
  public function getOrCreate($id = null, $item_id = null, $user_id = null, $group_id = null)
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
                        return null;
                    }
                    
                    $res_submission = $this->getMapper()->get(null, $item_id, $user_id);
                    if($res_submission->count() <= 0){
                        $id = $this->create($item_id);
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

        if(null === $m_submission->getPostId()){
            $post_id = $this->getServicePost()->add(null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,'submission');
            $m_submission->setPostId($post_id);
            $this->getMapper()->update($m_submission);
        }

      return $m_submission;
  }
  
  /**
   * Create Submission
   * 
   * @param int $item_id
   */
    public function create($item_id)
    {
        $post_id = $this->getServicePost()->add(null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,'submission');
        $this->getMapper()->insert($this->getModel()->setItemId($item_id)->setPostId($post_id));

        return (int)$this->getMapper()->getLastInsertValue();
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
        $submission_id = $this->create($item_id);
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
