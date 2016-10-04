<?php
/**
 * 
 * TheStudnet (http://thestudnet.com)
 *
 * Post Subscription
 *
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class PostSubscription
 */
class PostSubscription extends AbstractService
{
    /**
     * Add Post Subscription
     * 
     * @param string $libelle
     * @param int $post_id
     * @param string $last_date
     * @return int
     */
    public function add($libelle, $post_id, $last_date)
    {
        $m_post_subscription = $this->getModel()
            ->setLibelle($libelle)
            ->setPostId($post_id);
        
        $res_post_subscription = $this->getMapper()->select($m_post_subscription);
        
        if ($res_post_subscription->count() <= 0) {
            $ret = $this->getMapper()->insert($m_post_subscription->setLastDate($last_date));
        } else {
            $ret =$this->getMapper()->update($this->getModel()->setLastDate($last_date),
                ['libelle' => $libelle, 'post_id' => $post_id]);
        }
        
        return $ret;
    }
    
    /**
     * 
     * @param string $libelle
     * @param int $post_id
     * @return int
     */
    public function delete($libelle, $post_id)
    {
        $m_post_subscription = $this->getModel()
            ->setLibelle($libelle)
            ->setPostId($post_id);
    
        return $this->getMapper()->delete($m_post_subscription);
    }
    
    public function addHashtag($ar, $id, $date)
    {
        foreach ($ar as $n) {
            if(substr($n,0,1) === '@') {
                $tab = json_decode(str_replace("'", "\"", substr($n, 1)),true);
                // remonte le post des abonner a la personne tagé
                $this->add('U'.$tab[0].$tab[1], $id, $date);
            }
        }
    }
    
    public function addLike($post_id, $date)
    {
        $user_id = $this->getServiceUser()->getIdentity()['id'];
        $m_post = $this->getServicePost()->getLite($post_id);
        $m_post_like = $this->getServicePostLike()->getLite($post_id);
        
        // remonte le post des abonner a la cible
        $this->add($this->getTarget($m_post), $post_id, $date);
        // remonte le post des abonner à la personne qui like
        $this->add($this->getUserLike($m_post_like), $post_id, $date);
    }
    
    /**
     * Ajout post
     * 
     * subsribe target
     * 
     * @param int $post_id
     * @param string $date
     */
    public function addOrUpdatePost($post_id, $date)
    {
        $m_post = $this->getServicePost()->getLite($post_id);
        
        // remonte les post des abonner a la personne qui poste ( user org page )
        $this->add($this->getUser($m_post), $post_id, $date);
        //remont les post des abonner a la cible
        $this->add($this->getTarget($m_post), $post_id, $date);
    }
    
    private function getUserLike(\Application\Model\PostLike $m_post_like)
    {
        switch (true) {
            case (is_numeric($m_post_like->getOrganizationId())):
                $u = 'UO'.$m_post_like->getOrganizationId();
                break;
            case (is_numeric($m_post_like->getPageId())):
                $u = 'UP'.$m_post_like->getPageId();
                break;
            default:
                $u ='UU'.$m_post_like->getUserId();
                break;
        }
    
        return $u;
    }
    
    private function getUser($m_post)
    {
        switch (true) {
            case (is_numeric($m_post->getOrganizationId())):
                $u = 'UO'.$m_post->getOrganizationId();
                break;
            case (is_numeric($m_post->getPageId())):
                $u = 'UP'.$m_post->getPageId();
                break;
            default:
                $u ='UU'.$m_post->getUserId();
                break;
        }
    
        return $u;
    }
    
    private function getTarget($m_post)
    {
        switch (true) {
            case (is_numeric($m_post->getTCourseId())):
                $t = 'TC'.$m_post->getTCourseId();
                break;
            case (is_numeric($m_post->getTOrganizationId())):
                $t = 'TO'.$m_post->getTOrganizationId();
                break;
            case (is_numeric($m_post->getTPageId())):
                $t = 'TP'.$m_post->getTPageId();
                break;
            case (is_numeric($m_post->getTUserId())):
                $t = 'TU'.$m_post->getTUserId();
                break;
            default:
                $t = $this->getTarget($this->getServicePost()->getLite($m_post->getOriginId()));
                break;
        }
    
        return $t;
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
    
    /**
     * Get Service Post Like
     *
     * @return \Application\Service\PostLike
     */
    private function getServicePostLike()
    {
        return $this->container->get('app_service_post_like');
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