<?php

namespace Application\Service;

use Dal\Service\AbstractService;
use Application\Model\PostSubscription as ModelPostSubscription;

class PostLike extends AbstractService
{
    /**
     * Add Liek to Post
     *
     * @param int $post_id
     * @param int $type
     * @throws \Exception
     * @return int
     */
    public function add($post_id, $type = 1)
    {
        $res = null;
        $user_id = $this->getServiceUser()->getIdentity()['id'];
        $m_post_like = $this->getModel()
            ->setPostId($post_id)
            ->setUserId($user_id);
    
        if ($this->getMapper()->select($m_post_like)->count() > 0) {
            $m_post_like->setIsLike(true);
            $res = $this->getMapper()->update($m_post_like, [
                'post_id' => $post_id, 
                'user_id' => $user_id
            ]);
        } else {
            $date = (new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s');
            $m_post_like->setIsLike(true)->setCreatedDate($date);

            if ($this->getMapper()->insert($m_post_like) <= 0) {
                throw new \Exception('error add like');
            }
            $res = $this->getMapper()->getLastInsertValue();
            
            /*
             * Subscription
             */
            $m_post = $this->getServicePost()->getLite($post_id);
            $m_post_like = $this->getLite($res);
            $sub_post = [
                'P'.$this->getServicePost()->getOwner($m_post), 
                'P'.$this->getServicePost()->getTarget($m_post), 
                'P'.$this->getUserLike($m_post_like),
            ];
            $sub_event = [
                'E'.$this->getServicePost()->getOwner($m_post), 
                'E'.$this->getServicePost()->getTarget($m_post),
                'E'.$this->getUserLike($m_post_like),
            ];
            
            $this->getServicePostSubscription()->add($sub_post, $post_id, $date, ModelPostSubscription::ACTION_LIKE);
            $this->getServiceEvent()->userLike($sub_event, $post_id);
        }
    
        return $res;
    }
    
    /**
     * UnLike Post
     * 
     * @toto check que le user soit bien encore admin de la page ou de lorganization
     * 
     * @param int $post_id
     * @return int
     */
    public function delete($post_id)
    {
        return $this->getMapper()->update($this->getModel()->setIsLike(false), [
            'post_id' => $post_id, 'user_id' => $this->getServiceUser()->getIdentity()['id']]);
    }
    
    /**
     * Get Post Like Lite
     *
     * @param int $id
     * @return \Application\Model\PostLike
     */
    public function getLite($id)
    {
        $res_post_like = $this->getMapper()->select($this->getModel()->setId($id));
        
        return (is_array($id)) ? 
            $res_post_like : 
            $res_post_like->current();
    }
    
    public function getUserLike(\Application\Model\PostLike $m_post_like)
    {
        switch (true) {
            case (is_numeric($m_post_like->getOrganizationId())):
                $u = 'O'.$m_post_like->getOrganizationId();
                break;
            case (is_numeric($m_post_like->getPageId())):
                $u = 'P'.$m_post_like->getPageId();
                break;
            default:
                $u ='U'.$m_post_like->getUserId();
                break;
        }
    
        return $u;
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
     * Get Service Event.
     *
     * @return \Application\Service\Event
     */
    private function getServiceEvent()
    {
        return $this->container->get('app_service_event');
    }
    
    /**
     * Get Service Post Like
     *
     * @return \Application\Service\Post
     */
    private function getServicePost()
    {
        return $this->container->get('app_service_post');
    }
    
    /**
     * Get Service Post Like
     *
     * @return \Application\Service\PostSubscription
     */
    private function getServicePostSubscription()
    {
        return $this->container->get('app_service_post_subscription');
    }
}