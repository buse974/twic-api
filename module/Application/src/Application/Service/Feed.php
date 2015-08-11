<?php
namespace Application\Service;

use Dal\Service\AbstractService;
use Zend\Db\Sql\Predicate\IsNull;

class Feed extends AbstractService
{

    /**
     * Add feed
     *
     * @invokable
     *
     * @param string $content            
     * @param string $link            
     * @param string $video            
     * @param string $picture            
     * @param string $document            
     *
     * @return integer
     */
    public function add($content = null, $link = null, $video = null, $picture = null, $document = null)
    {
        $user = $this->getServiceUser()->getIdentity()['id'];
        
        $m_feed = $this->getModel()
            ->setContent($content)
            ->setUserId($user)
            ->setLink($link)
            ->setVideo($video)
            ->setPicture($picture)
            ->setDocument($document)
            ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
        
        if ($this->getMapper()->insert($m_feed) <= 0) {
            new \Exception('error insert feed');
        }
        
        return $this->getMapper()->getLastInsertValue();
    }

    /**
     * Delete Feed
     *
     * @invokable
     *
     * @param integer $id            
     *
     * @return integer
     */
    public function delete($id)
    {
        $user = $this->getServiceUser()->getIdentity()['id'];
        
        $m_feed = $this->getModel()->setDeletedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
        
        return $this->getMapper()->update($m_feed, array('user_id' => $user, 'id' => $id));
    }

    /**
     * Add Comment Feed
     *
     * @invokable
     *
     * @param integer $id            
     * @param string $content            
     *
     * @return integer
     */
    public function addComment($id, $content)
    {
        return $this->getServiceFeedComment()->add($content, $id);
    }

    /**
     * Delete Comment Feed
     *
     * @invokable
     *
     * @param integer $id            
     *
     * @return integer
     */
    public function deleteComment($id)
    {
        return $this->getServiceFeedComment()->delete($id);
    }

    /**
     * Get List Comment Feed
     *
     * @invokable
     *
     * @param integer $id
     *
     */
    public function GetListComment($id)
    {
        return $this->getServiceFeedComment()->getList($id);
    }
    
    /**
     * GetList Feed
     *
     * @invokable          
     *
     */
    public function getList()
    {
        $me = $this->getServiceUser()->getIdentity()['id'];
        $res_contact = $this->getServiceContact()->getList();
        
        $user = [$me];
        foreach ($res_contact as $m_contact) {
            $user[] = $m_contact->getContact()['id'];
        }

        return $this->getMapper()->getList($user);
    }

    /**
     *
     * @return \Application\Service\FeedComment
     */
    public function getServiceFeedComment()
    {
        return $this->serviceLocator->get('app_service_feed_comment');
    }
    
    /**
     *
     * @return \Application\Service\Contact
     */
    public function getServiceContact()
    {
        return $this->serviceLocator->get('app_service_contact');
    }
    
    /**
     *
     * @return \Application\Service\User
     */
    public function getServiceUser()
    {
        return $this->serviceLocator->get('app_service_user');
    }
}
