<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Activity
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class Activity.
 */
class Report extends AbstractService
{
    /**
     * Create Report.
     * 
     * @invokable
     *
     * @param int $user_id
     * @param int $post_id
     * @param int $comment_id
     * @param string $reason
     * @param string $description
     *
     * @return int
     */
    public function add( $reason, $description = null, $user_id = null, $post_id = null, $page_id = null)
    {
        
        $identity = $this->getServiceAuth()->getIdentity();
        $m_report = $this->getModel()
            ->setReporterId($identity->getId())
            ->setUserId($user_id)
            ->setPostId($post_id)
            ->setPageId($page_id);
        
        if($this->getMapper()->select($m_report)->count() > 0){
            throw new \Exception('Duplicate report');
        }
        $m_report
            ->setReason($reason)
            ->setDescription($description)
            ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));

        if ($this->getMapper()->insert($m_report) <= 0) {
            throw new \Exception('Error during report');
        }

        return $this->getMapper()->getLastInsertValue();
    }
    
    /**
     * Get List of Reports.
     * 
     * @invokable
     * 
     * @param array $filter
     * @param bool $treated
     *
     * @return array
     */
    public function getList($filter, $treated = null)
    {
        $mapper = $this->getMapper();
        $res = $mapper->usePaginator($filter)->getList($treated);
        return ['list' => $res,'count' => $mapper->count()];
    }
    

    /**
     * Get List of Reports.
     * 
     * @invokable
     * 
     * @param int $id
     * @param bool $treat
     *
     * @return int
     */
    public function treat($validate, $user_id = null, $post_id = null,  $description = null, $page_id = null)
    {
        $m_report = $this->getModel()->setValidate($validate)->setTreated(1)->setTreatmentDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
        if(null !== $post_id){
            if(1 === $validate){
                $this->getServicePost()->delete($post_id);
            }
            else{
                $this->getServicePost()->reactivate($post_id);
            }
        }
         if(null !== $page_id){
            if(1 === $validate){
                $this->getServicePage()->delete($page_id);
            }
            else{
                $this->getServicePage()->reactivate($page_id);
            }
        }
        
        if(null !== $user_id){
            $this->getServiceUser()->suspend($user_id, $validate, $description);
        }
        return $this->getMapper()->update($m_report, [ 'post_id' => $post_id, 'user_id' => $user_id, 'page_id' => $page_id]);
    }
    
    
    
    /**
     * Get Service AuthenticationService.
     *
     * @return \Zend\Authentication\AuthenticationService
     */
    private function getServiceAuth()
    {
        return $this->container->get('auth.service');
    }
    
    /**
     * Get Service Feed.
     *
     * @return \Application\Service\Feed
     */
    private function getServicePost()
    {
        return $this->container->get('app_service_post');
    } 
    
    
    /**
     * Get Service Page.
     *
     * @return \Application\Service\Feed
     */
    private function getServicePage()
    {
        return $this->container->get('app_service_page');
    } 
    
    /**
     * Get Service EventComment.
     *
     * @return \Application\Service\EventComment
     */
    private function getServiceEventComment()
    {
        return $this->container->get('app_service_event_comment');
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


