<?php
/**
 * 
 * TheStudnet (http://thestudnet.com)
 *
 * Post
 *
 */
namespace Application\Service;

use Application\Model\Page as ModelPage;
use Dal\Service\AbstractService;
use Application\Model\Role as ModelRole;
use Application\Model\PostSubscription as ModelPostSubscription;
use Dal\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Predicate\IsNull;
use function GuzzleHttp\json_encode;

/**
 * Class Post
 */
class Post extends AbstractService
{
    
    /**
     * Add Post
     * 
     * @invokable
     * 
     * @param string $content
     * @param string $link
     * @param string $picture
     * @param string $name_picture
     * @param string $link_title
     * @param string $link_desc
     * @param int $parent_id
     * @param int $t_page_id
     * @param int $t_organization_id
     * @param int $t_user_id
     * @param int $t_course_id
     * @param int $page_id
     * @param int $organization_id
     * @param int $lat
     * @param int $lng
     * @param array $docs
     * @param string $data
     * @param string $event
     * @param string $uid
     * @param array $sub
     * 
     * @return \Application\Model\Post
     */
    public function add($content, $picture = null,  $name_picture = null, $link = null, $link_title = null,  $link_desc = null, $parent_id = null,  
        $t_page_id = null,  $t_organization_id = null,  $t_user_id = null,  $t_course_id = null, $page_id = null, $organization_id = null, $lat =null, 
        $lng = null ,$docs = null, $data = null, $event = null, $uid = null, $sub = null)
    {
        $user_id = $this->getServiceUser()->getIdentity()['id'];
        $origin_id = null;
        if (null !== $parent_id) {
            $m_post = $this->getMapper()->select($this->getModel()->setId($parent_id))->current();
            $origin_id = (is_numeric($m_post->getOriginId())) ?
                $m_post->getOriginId()  :
                $m_post->getId();
            $uid = $m_post->getUid();

        }
        
        $uid = (($uid) && !empty($uid)) ? $uid:false;
        $is_notif = !!$uid;
        
        $date = (new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s');
        if (!$is_notif && null === $parent_id && null === $t_course_id && null === $t_organization_id && null === $t_page_id && null === $t_user_id) {
            $t_user_id = $user_id;
        }
        
        $user_id = $this->getServiceUser()->getIdentity()['id'];
        $m_post = $this->getModel()
            ->setContent($content)
            ->setPicture($picture)
            ->setNamePicture($name_picture)
            ->setUserId($user_id)
            ->setLink($link)
            ->setLinkTitle($link_title)
            ->setLinkDesc($link_desc)
            ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'))
            ->setOrganizationId($organization_id)
            ->setPageId($page_id)
            ->setLat($lat)
            ->setLng($lng)
            ->setParentId($parent_id)
            ->setOriginId($origin_id)
            ->setTPageId($t_page_id)
            ->setTOrganizationId($t_organization_id)
            ->setTUserId($t_user_id)
            ->setTCourseId($t_course_id)
            ->setUid($uid);
        
        if($this->getMapper()->insert($m_post) <= 0) {
            throw new \Exception('error add post');
        }
        $id = $this->getMapper()->getLastInsertValue();
        
        if(null !== $docs) {
            $this->getServicePostDoc()->_add($id, $docs);
        }
        
        
        $base_id = ($origin_id) ? $origin_id:$id;
        $m_post_base = $this->getLite($base_id);
        $is_private_page = (is_numeric($m_post_base->getTPageId()) && ($this->getServicePage()->getLite($m_post_base->getTPageId())->getConfidentiality() === ModelPage::CONFIDENTIALITY_PRIVATE));

        // si c pas une notification on gére les hastags
        if(!$is_notif) {
            $ar = array_filter(explode(' ', str_replace(["\r\n","\n","\r"], ' ', $content)), function ($v) {
                return (strpos($v, '#') !== false) || (strpos($v, '@') !== false);
            });
            
            $this->getServiceHashtag()->add($ar, $id);
            $this->getServicePostSubscription()->addHashtag($ar, $id, $date);
        }
            
        $pevent = [];
        // S'IL Y A UNE CIBLE A LA BASE ON NOTIFIE
        $et = $this->getTarget($m_post_base);
        if(false !== $et) {
            $pevent = $pevent + ['P'.$et];
        }
        
        // if ce n'est pas un page privée
        if(!$is_private_page &&  !$is_notif) {
            $pevent = $pevent + ['P'.$this->getOwner($m_post_base)];
        }
        
        if($parent_id && $origin_id) {
            // SI N'EST PAS PRIVATE ET QUE CE N'EST PAS UNE NOTIF -> ON NOTIFIE LES AMIES DES OWNER
            $m_post = $this->getLite($id);
            if(!$is_private_page &&  !$is_notif) {
                $pevent = $pevent + ['P'.$this->getOwner($m_post)];
            }
            // SI NOTIF ET QUE LE PARENT N'A PAS DE TARGET ON RECUPERE TTES LES SUBSCRIPTIONS
            if($is_notif && null === $sub && $et === false) {
                $sub = $this->getServicePostSubscription()->getListLibelle($origin_id);
            }
        }
        
        if(!empty($sub)) {
            $pevent = $pevent + $sub;
        }
        
        $this->getServicePostSubscription()->add(
            array_unique($pevent), 
            $base_id, 
            $date,
            ((!empty($event))? $event:(($base_id!==$id) ? ModelPostSubscription::ACTION_COM : ModelPostSubscription ::ACTION_CREATE)), 
            $user_id, 
            (($base_id!==$id) ? $id:null), 
            $data);
        
        return $this->get($id);
    }

    /**
     * 
     * @param string $uid
     * @param string $content
     * @param string $data
     * @param string $event
     * @param string $sub
     * @param int $parent_id
     * @param int $t_page_id    
     * @param int $t_organization_id
     * @param int $t_user_id
     * @param int $t_course_id
     */
    public function addSys($uid, $content, $data, $event, $sub = null, $parent_id = null, $t_page_id = null,$t_organization_id = null,$t_user_id = null,$t_course_id = null) 
    {
        if(!is_array($sub)) {
            $sub = [$sub];
        }

        return $this->add($content, null,null,null,null,null,$parent_id,$t_page_id,$t_organization_id,$t_user_id,$t_course_id,null,null,null,null,null, 
            $data, $event, $uid, $sub);
    }
    
    /**
     * @param string $uid
     * @param string $content
     * @param string $data
     * @param string $event
     * @param array $sub
     * @return int
     */
    public function updateSys($uid, $content, $data, $event, $sub = null)
    {
        if(!is_array($sub)) {
            $sub = [$sub];
        }
    
        return $this->update(null, $content, null,null, null, null, null, null, null, null, $data, $event, $uid, $sub); 
    }
    
    /**
     * Update Post
     * 
     * @invokable
     * 
     * @param int $id
     * @param string $content
     * @param string $link
     * @param string $picture
     * @param string $name_picture
     * @param string $link_title
     * @param string $link_desc
     * @param int $lat
     * @param int $lng
     * @param arrray $docs
     * @param string $data
     * @param string $event
     * @param int $uid
     * @param array $sub
     * @return int
     */
    public function update($id = null, $content = null, $link = null, $picture = null, $name_picture = null, $link_title = null, 
        $link_desc = null, $lat = null, $lng = null, $docs =null, $data = null, $event = null, $uid = null, $sub = null)
    {
        
        $user_id = $this->getServiceUser()->getIdentity()['id'];
        $date = (new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s');

        
        $m_post_base = ($uid !== null && $id === null) ? $this->getLite(null, $uid) : $this->getLite($id);
        $id = $m_post_base->getId();
        
        $w = ($uid !== null) ?  ['id' => $id, 'user_id' => $user_id] : ['uid' => $uid];
        
        $uid = (is_string($uid) && !empty($uid)) ? $uid:false;
        $event = (is_string($event) && !empty($event)) ? $event:false;
        $is_notif = ($uid && $event);
        $m_post = $this->getModel()
            ->setContent($content)
            ->setLink(($link==='')?new IsNull():$link)
            ->setPicture(($picture==='')?new IsNull():$picture)
            ->setNamePicture(($name_picture==='')?new IsNull():$name_picture)
            ->setLinkTitle(($link_title==='')?new IsNull():$link_title)
            ->setLinkDesc(($link_desc==='')?new IsNull():$link_desc)
            ->setLat($lat)
            ->setLng($lng)
            ->setUpdatedDate($date);
        
        if(null !== $docs) {
            $this->getServicePostDoc()->replace($id, $docs);
        }
        
        $this->getMapper()->update($m_post, $w);
        $is_private_page = (is_numeric($m_post_base->getTPageId()) && ($this->getServicePage()->getLite($m_post_base->getTPageId())->getConfidentiality() === ModelPage::CONFIDENTIALITY_PRIVATE));

        // si c pas une notification on gére les hastags
        if(!$is_notif) {
            $ar = array_filter(explode(' ', str_replace(["\r\n","\n","\r"], ' ', $content)), function ($v) {
                return (strpos($v, '#') !== false) || (strpos($v, '@') !== false);
            });
        
            $this->getServiceHashtag()->add($ar, $id);
            $this->getServicePostSubscription()->addHashtag($ar, $id, $date, ModelPostSubscription::ACTION_UPDATE);
        }
        
        $pevent = [];
        // S'IL Y A UNE CIBLE A LA BASE ON NOTIFIE
        $et = $this->getTarget($m_post_base);
        if(false !== $et) {
            $pevent = $pevent + ['P'.$et];
        }
        
        // if ce n'est pas un page privée
        if(!$is_private_page &&  !$is_notif) {
            $pevent = $pevent + ['P'.$this->getOwner($m_post_base)];
        }
        
        if(!empty($sub)) {
            $pevent = $pevent + $sub;
        }
        
        $this->getServicePostSubscription()->add(
            array_unique($pevent),
            $id,
            $date,
            (!empty($event)? $event:ModelPostSubscription::ACTION_UPDATE ),
            $user_id,
            null,
            $data);
        
        //$m_post = $this->get($id);
        //$sub_post = ['U'.$this->getOwner($m_post), 'U'.$this->getTarget($m_post)];

        return $m_post;
    }
    
    /**
     * Delete Post
     * 
     * @invokable
     * 
     * @param int $id
     * @return int
     */
    public function delete($id)
    {
        //$this->deleteSubscription($id);
        
        $identity = $this->getServiceUser()->getIdentity();
        $is_sadmin = in_array(ModelRole::ROLE_SADMIN_STR, $identity['roles']);
        $m_post = $this->getModel()
            ->setDeletedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
        if(!$is_sadmin){
            return $this->getMapper()->update($m_post, ['id' => $id, 'user_id' => $identity['id']]);
        }
        else{
             return $this->getMapper()->update($m_post, ['id' => $id]);
        }
    }
    
      /**
     * Reactivate Post
     * 
     * @invokable
     * 
     * @param int $id
     * @return int
     */
    public function reactivate($id)
    {
        //$this->deleteSubscription($id);
        
        $m_post = $this->getModel()->setDeletedDate(new \Zend\Db\Sql\Predicate\IsNull());
    
        return $this->getMapper()->update($m_post, ['id' => $id]);
    }
    
    /**
     * Get Post
     * 
     * @invokable
     * 
     * @param int $id
     * @return \Application\Model\Post
     */
    public function get($id) 
    {
        $res_post = $this->_get($id);
        foreach ($res_post as $m_post) {
            $m_post->setComments($this->getMapper()->getList(null, null, null, null, null, $m_post->getId()));
            $m_post->setDocs($this->getServicePostDoc()->getList($m_post->getId()));
        }
        
        $res_post->rewind();
        return ((is_array($id)) ? $res_post : $res_post->current());
    }
    
    /**
     * Get Post
     *
     * @invokable
     *
     * @param int $id
     * @return ResultSet
     */
    public function _get($id, $is_mobile = false)
    {
        $identity = $this->getServiceUser()->getIdentity();
        $me = $identity['id'];
        $is_sadmin = in_array(ModelRole::ROLE_SADMIN_STR, $identity['roles']);
        
        return  $this->getMapper()->get($me, $id, $is_sadmin, $is_mobile);
    }
    
    /**
     * Get Post
     *
     * @invokable
     *
     * @param int $id
     * @return \Application\Model\Post
     */
    public function m_get($id)
    {
        $res_post = $this->_get($id, true);
        
        foreach ($res_post as $m_post) {
            $m_post->setDocs($this->getServicePostDoc()->getList($m_post->getId()));
            $m_post->setSubscription($this->getServicePostSubscription()->getLastLite($m_post->getId()));
        }
        
        return (is_array($id) ? $res_post->toArray(['id']): $res_post->current());
    }
    
    /**
     * Get List Post
     * 
     * @invokable
     * 
     * @param array $filter
     * @param int $user_id
     * @param int $page_id
     * @param int $organization_id
     * @param int $course_id
     * @param int $parent_id
     */
    public function getList($filter = null, $user_id = null, $page_id = null, $organization_id = null, $course_id = null, $parent_id = null)
    {
        $me = $this->getServiceUser()->getIdentity()['id'];
        $mapper = (null !== $filter) ? 
            $this->getMapper()->usePaginator($filter) : 
            $this->getMapper();
        
        $res_posts = $mapper->getList($me, $page_id, $organization_id, $user_id, $course_id, $parent_id);
        if(null === $parent_id){
            foreach ($res_posts as $m_post) {
                $m_post->setComments($this->getMapper()->getList($me, null, null, null, null, $m_post->getId()));
                $m_post->setDocs($this->getServicePostDoc()->getList($m_post->getId()));
                $m_post->setSubscription($this->getServicePostSubscription()->getLast($m_post->getId()));
            }
        }
        
        return (null !== $filter) ? 
            ['count' => $mapper->count(), 'list' => $res_posts]:
            $res_posts;
    }
    
    /**
     * Get List Post
     *
     * @invokable
     *
     * @param array $filter
     * @param int $user_id
     * @param int $page_id
     * @param int $organization_id
     * @param int $course_id
     * @param int $parent_id
     */
    public function getListId($filter = null, $user_id = null, $page_id = null, $organization_id = null, $course_id = null, $parent_id = null)
    {
        $me = $this->getServiceUser()->getIdentity()['id'];
        $mapper = (null !== $filter) ?
            $this->getMapper()->usePaginator($filter) :
            $this->getMapper();
    
        $res_posts = $mapper->getListId($me, $page_id, $organization_id, $user_id, $course_id, $parent_id);
        
        return (null !== $filter) ?
            ['count' => $mapper->count(), 'list' => $res_posts]:
            $res_posts;
    }
    
    /**
     * Get Post Lite
     * 
     * @param int $id
     * @param int $uid
     * @return \Application\Model\Post
     */
    public function getLite($id = null, $uid = null)
    {
        return $this->getMapper()->select($this->getModel()->setId($id)->setUid($uid))->current();
    }
    
    /**
     * Like post 
     * 
     * @invokable
     * 
     * @param int $post_id
     */
    public function like($id)
    {
        return $this->getServicePostLike()->add($id);
    }
    
    /**
     * UnLike Post
     * 
     * @invokable
     * 
     * @param int $id
     */
    public function unlike($id) 
    {
        return $this->getServicePostLike()->delete($id);
    }
    
    public function getOwner($m_post)
    {
        switch (true) {
            case (is_numeric($m_post->getOrganizationId())):
                $u = 'O'.$m_post->getOrganizationId();
                break;
            case (is_numeric($m_post->getPageId())):
                $u = 'P'.$m_post->getPageId();
                break;
            default:
                $u ='U'.$m_post->getUserId();
                break;
        }
    
        return $u;
    }
    
    public function getTarget($m_post)
    {
        switch (true) {
            case (is_numeric($m_post->getTCourseId())):
                $t = 'C'.$m_post->getTCourseId();
                break;
            case (is_numeric($m_post->getTOrganizationId())):
                $t = 'O'.$m_post->getTOrganizationId();
                break;
            case (is_numeric($m_post->getTPageId())):
                $t = 'P'.$m_post->getTPageId();
                break;
            case (is_numeric($m_post->getTUserId())):
                $t = 'U'.$m_post->getTUserId();
                break;
            default:
                $t = false;
                break;
        }
    
        return $t;
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
     * Get Service Post Doc
     *
     * @return \Application\Service\PostDoc
     */
    private function getServicePostDoc()
    {
        return $this->container->get('app_service_post_doc');
    }
    
    /**
     * Get Service Page 
     *
     * @return \Application\Service\Page
     */
    private function getServicePage()
    {
        return $this->container->get('app_service_page');
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
     * Get Service Post Like
     *
     * @return \Application\Service\PostSubscription
     */
    private function getServicePostSubscription()
    {
        return $this->container->get('app_service_post_subscription');
    }
    
    /**
     * Get Service Post Like
     *
     * @return \Application\Service\Hashtag
     */
    private function getServiceHashtag()
    {
        return $this->container->get('app_service_hashtag');
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
    
}
