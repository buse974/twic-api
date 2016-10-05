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
        if(!is_array($libelle)) {
            $libelle = [$libelle];
        }
        
        foreach ($libelle as $l) {
            $m_post_subscription = $this->getModel()
                ->setLibelle($l)
                ->setPostId($post_id);
            $res_post_subscription = $this->getMapper()->select($m_post_subscription);
            if ($res_post_subscription->count() <= 0) {
                $ret = $this->getMapper()->insert($m_post_subscription->setLastDate($last_date));
            } else {
                $ret =$this->getMapper()->update($this->getModel()->setLastDate($last_date),
                    ['libelle' => $l, 'post_id' => $post_id]);
            }
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