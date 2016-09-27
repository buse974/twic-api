<?php
/**
 * 
 * TheStudnet (http://thestudnet.com)
 *
 * Post
 *
 */
namespace Application\Service;

use Dal\Service\AbstractService;

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
     * @param array $docs
     */
    public function add($content, $picture = null,  $name_picture = null, $link = null, $link_title = null,  $link_desc = null, $parent_id = null,  $t_page_id = null,  $t_organization_id = null,  $t_user_id = null,  $t_course_id = null, $docs = null)
    {
        $m_post = $this->getModel()
            ->setContent($content)
            ->setPicture($picture)
            ->setNamePicture($name_picture)
            ->setUserId($this->getServiceUser()->getIdentity()['id'])
            ->setLink($link)
            ->setLinkTitle($link_title)
            ->setLinkDesc($link_desc)
            ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'))
            ->setParentId($parent_id)
            ->setTPageId($t_page_id)
            ->setTOrganizationId($t_organization_id)
            ->setTUserId($t_user_id)
            ->setTCourseId($t_course_id);
       
        $this->getMapper()->insert($m_post);
        $id = $this->getMapper()->getLastInsertValue();
        
        if(null !== $docs) {
            $this->getServicePostDoc()->_add($id, $docs);
        }
        
        return $id;
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
     * @param arrray $docs
     * @return int
     */
    public function update($id, $content = null, $link = null, $picture = null, $name_picture = null, $link_title = null, $link_desc = null, $docs =null)
    {
        $m_post = $this->getModel()
            ->setContent($content)
            ->setLink($link)
            ->setPicture($picture)
            ->setNamePicture($name_picture)
            ->setLinkTitle($link_title)
            ->setLinkDesc($link_desc)
            ->setUpdatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
        
        if(null !== $docs) {
            $this->getServicePostDoc()->replace($id, $docs);
        }
            
        return $this->getMapper()->update($m_post, ['id' => $id, 'user_id' => $this->getServiceUser()->getIdentity()['id']]);
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
        $m_post = $this->getModel()
            ->setDeletedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
    
        return $this->getMapper()->update($m_post, ['id' => $id, 'user_id' => $this->getServiceUser()->getIdentity()['id']]);
    }
    
    /**
     * Get Post
     * 
     * @invokable
     * 
     * @param int $id
     */
    public function get($id) 
    {
        $m_post =  $this->getMapper()->select($this->getModel()->setId($id))->current();
        $m_post->setDocs($this->getServicePostDoc()->getList($id));
        
        return $m_post;
    }
    
    /**
     * Get List Post
     * 
     * @invokable
     */
    public function getList()
    {
        $user_id = $this->getServiceUser()->getIdentity()['id'];
        
        return $this->getMapper()->getList($user_id);
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
     * Get Service Post Like
     *
     * @return \Application\Service\PostLike
     */
    private function getServicePostLike()
    {
        return $this->container->get('app_service_post_like');
    }

}