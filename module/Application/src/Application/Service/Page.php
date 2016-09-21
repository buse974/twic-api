<?php
/**
 * 
 * TheStudnet (http://thestudnet.com)
 *
 * Page
 *
 */
namespace Application\Service;

use Dal\Service\AbstractService;
use Application\Model\PageUser as ModelPageUser;

/**
 * Class Page
 */
class Page extends AbstractService
{

    /**
     * Add Page
     *
     * @invokable
     *
     * @param string $title            
     * @param string $logo            
     * @param string $background            
     * @param string $description            
     * @param int $confidentiality            
     * @param string $type            
     * @param string $admission            
     * @param string $start_date            
     * @param string $end_date            
     * @param string $location            
     * @param int $organization_id            
     * @param int $page_id            
     * @param array $users            
     * @param array $tags            
     * @param array $docs            
     * @return int
     */
    public function add($title, $logo,  $description, $confidentiality, $type, $admission = 'invite', $background = null, $start_date = null, $end_date = null, $location = null, $organization_id = null, $page_id = null, $users = [], $tags = [], $docs = [])
    {
        $user_id = $this->getServiceUser()->getIdentity()['id'];
        
        $m_page = $this->getModel()
            ->setTitle($title)
            ->setLogo($logo)
            ->setBackground($background)
            ->setDescription($description)
            ->setConfidentiality($confidentiality)
            ->setAdmission($admission)
            ->setStartDate($start_date)
            ->setEndDate($end_date)
            ->setLocation($location)
            ->setType($type)
            ->setUserId($user_id)
            ->setOrganizationId($organization_id)
            ->setPageId($page_id);
        $this->getMapper()->insert($m_page);
        
        $id = $this->getMapper()->getLastInsertValue();
        
        if (! is_array($users)) {
            $users = [];
        }
        if (! is_array($docs)) {
            $docs = [];
        }
        
        $is_present = false;
        foreach ($users as $ar_u) {
            if ($ar_u['user_id'] === $user_id) {
                $is_present = true;
                $ar_u['role'] = ModelPageUser::ROLE_ADMIN;
                $ar_u['state'] = ModelPageUser::STATE_MEMBER;
                
                break;
            }
        }
        
        if (! $is_present) {
            $users[] = ['user_id' => $user_id,'role' => ModelPageUser::ROLE_ADMIN,'state' => ModelPageUser::STATE_MEMBER];
        }
        if (null !== $users) {
            $this->getServicePageUser()->_add($id, $users);
        }
        if (null !== $tags) {
            $this->getServicePageTag()->_add($id, $tags);
        }
        if (null !== $docs) {
            $this->getServicePageDoc()->_add($id, $docs);
        }
        
        return $id;
    }

    /**
     * Update Page
     *
     * @invokable
     *
     * @param int $id            
     * @param string $title            
     * @param string $logo            
     * @param string $background            
     * @param string $description            
     * @param int $confidentiality            
     * @param string $type            
     * @param string $admission            
     * @param string $start_date            
     * @param string $end_date            
     * @param string $location            
     * @param int $organization_id            
     * @param int $page_id            
     * @param array $users            
     * @param array $tags            
     * @param array $docs                    
     *
     * @return int
     */
    public function update($id, $title=null, $logo=null, $background=null, $description=null, $confidentiality=null, $type=null, $admission=null, $start_date = null, $end_date = null, $location = null, $organization_id = null, $page_id = null, $users = null, $tags = null, $docs = null)
    {
        $user_id = $this->getServiceUser()->getIdentity()['id'];
        $m_page = $this->getModel()
            ->setId($id)
            ->setTitle($title)
            ->setLogo($logo)
            ->setBackground($background)
            ->setDescription($description)
            ->setConfidentiality($confidentiality)
            ->setAdmission($admission)
            ->setStartDate($start_date)
            ->setEndDate($end_date)
            ->setLocation($location)
            ->setType($type)
            ->setUserId($user_id)
            ->setOrganizationId($organization_id)
            ->setPageId($page_id);
        
        if (null !== $users) {
            syslog(1, json_encode($users));
            $is_present = false;
            foreach ($users as $ar_u) {
                if ($ar_u['user_id'] === $user_id) {
                    $is_present = true;
                    $ar_u['role'] = ModelPageUser::ROLE_ADMIN;
                    $ar_u['state'] = ModelPageUser::STATE_MEMBER;
            
                    break;
                }
            }
            
            if (! $is_present) {
                $users[] = ['user_id' => $user_id,'role' => ModelPageUser::ROLE_ADMIN,'state' => ModelPageUser::STATE_MEMBER];
            }
            $this->getServicePageUser()->replace($id, $users);
        }
        if (null !== $tags) {
            $this->getServicePageTag()->replace($id, $tags);
        }
        if (null !== $docs) {
            $this->getServicePageDoc()->replace($id, $docs);
        }
        
        return $this->getMapper()->update($m_page);
    }

    /**
     * Delete Page
     *
     * @invokable
     *
     * @param int $id            
     * @return int
     */
    public function delete($id)
    {
        return $this->getMapper()->delete($m_page = $this->getModel()
            ->setId($id));
    }

    /**
     * Get Page
     *
     * @invokable
     *
     * @param int $id      
     * @param int $parent_id   
     * @param string $type      
     */
    public function get($id = null, $parent_id = null, $type = null)
    {
        if(null === $id && null === $parent_id) {
            throw new \Exception('Error: params is null');
        }
        
        $m_page = $this->getMapper()->get($id, $parent_id, $type)->current();
        if(null === $m_page){
              throw new \Exception('This page does not exist');
        }
        
        $m_page->setTags($this->getServicePageTag()
            ->getList($id));
        $m_page->setDocs($this->getServicePageDoc()
            ->getList($id));
        $m_page->setUsers($this->getServicePageUser()
            ->getList($id));
        
        return $m_page;
    }
    
    /**
     * Get Page
     *
     * @invokable
     * 
     * @param int $id
     * @param int $parent_id
     * @param int $user_id
     * @param int $organization_id
     * @throws \Exception
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getList($id = null, $parent_id = null, $user_id = null, $organization_id = null)
    {
        if(null === $id && null === $parent_id && null === $user_id && null === $organization_id) {
            throw new \Exception('Error: params is null');
        }
        
        $res_page = $this->getMapper()
        ->select($this->getModel()
                ->setId($id)
                ->setPageId($parent_id)
                ->setUserId($user_id)
                ->setOrganizationId($organization_id));
        
        foreach ($res_page as $m_page) {
            $m_page->setTags($this->getServicePageTag()
                ->getList($m_page->getId()));
            $m_page->setDocs($this->getServicePageDoc()
                ->getList($m_page->getId()));
            $m_page->setUsers($this->getServicePageUser()
                ->getList($m_page->getId()));
        }
        
        return $res_page;
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
     * Get Service Page User
     *
     * @return \Application\Service\PageUser
     */
    private function getServicePageUser()
    {
        return $this->container->get('app_service_page_user');
    }

    /**
     * Get Service Page Doc
     *
     * @return \Application\Service\PageDoc
     */
    private function getServicePageDoc()
    {
        return $this->container->get('app_service_page_doc');
    }

    /**
     * Get Service Page Tag
     *
     * @return \Application\Service\PageTag
     */
    private function getServicePageTag()
    {
        return $this->container->get('app_service_page_tag');
    }
}