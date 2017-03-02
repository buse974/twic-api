<?php
/**
 * TheStudnet (http://thestudnet.com)
 *
 * Page
 */
namespace Application\Service;

use Dal\Service\AbstractService;
use Application\Model\PageUser as ModelPageUser;
use Application\Model\Page as ModelPage;
use Application\Model\Role as ModelRole;

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
     * @param string $description
     * @param string $confidentiality
     * @param string $type
     * @param string $logo
     * @param string $admission
     * @param string $background
     * @param string $start_date
     * @param string $end_date
     * @param string $location
     * @param int    $organization_id
     * @param int    $page_id
     * @param array  $users
     * @param array  $tags
     * @param array  $docs
     *
     * @return int
     */
    public function add($title, $description, $confidentiality, $type, $logo = null, $admission = 'invite', $background = null, $start_date = null, $end_date = null, $location = null,
        $organization_id = null, $page_id = null, $users = [], $tags = [], $docs = [], $owner_id = null
    ) {
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

        if(null !== $owner_id){
            $m_page->setOwnerId($owner_id);
        }
        else{
            $m_page->setOwnerId($user_id);
        }
        $this->getMapper()->insert($m_page);
        $id = (int)$this->getMapper()->getLastInsertValue();

        if (! is_array($users)) {
            $users = [];
        }
        if (! is_array($docs)) {
            $docs = [];
        }


        $is_present = false;
        foreach ($users as $ar_u) {
            if ($ar_u['user_id'] === $m_page->getOwnerId()) {
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

        if ($confidentiality === ModelPage::CONFIDENTIALITY_PUBLIC) {
            $sub=[];
            if (null !== $page_id) {
                $sub[] = 'EP'.$page_id;
            } elseif (null !== $organization_id) {
                $sub[] = 'EO'.$organization_id;
            } else {
                $sub[] = 'EU'.$user_id;
            }

            $this->getServiceEvent()->pageNew($sub, $id);
            $this->getServicePost()->addSys(
                'PP'.$id, '', [
                'state' => 'create',
                'user' => $owner_id,
                'org' => $organization_id,
                'parent' => $page_id,
                'page' => $id,
                'type' => $type,
                ], 'create', null/*sub*/, null/*parent*/, $page_id/*page*/, $organization_id/*org*/, $owner_id/*user*/, null/*course*/, 'page'
            );
        }
        return $id;
    }

    /**
     * Update Page
     *
     * @invokable
     *
     * @param int    $id
     * @param string $title
     * @param string $logo
     * @param string $background
     * @param string $description
     * @param int    $confidentiality
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
     * @TODO Seuls admins de la page peuvent l'éditer (ou un studnet admin)
     *
     * @return int
     */
    public function update($id, $title=null, $logo=null, $background=null, $description=null, $confidentiality=null, $type=null, $admission=null, $start_date = null, $end_date = null, $location = null, $organization_id = null, $page_id = null, $users = null, $tags = null, $docs = null, $owner_id = null)
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
            ->setOrganizationId($organization_id)
            ->setPageId($page_id)
            ->setOwnerId($owner_id);

        if (null !== $users) {
            $is_present = false;
            foreach ($users as $ar_u) {
                if ($ar_u['user_id'] === $m_page->getOwnerId()) {
                    $is_present = true;
                    $ar_u['role'] = ModelPageUser::ROLE_ADMIN;
                    $ar_u['state'] = ModelPageUser::STATE_MEMBER;

                    break;
                }
            }
            $this->getServicePageUser()->replace($id, $users);
        }
        if (null !== $tags) {
            $this->getServicePageTag()->replace($id, $tags);
        }
        if (null !== $docs) {
            $this->getServicePageDoc()->replace($id, $docs);
        }
        if ($confidentiality !== null) {
            $tmp_m_post = $this->getMapper()->select($this->getModel()->setId($id))->current();
            if ($tmp_m_post->getConfidentiality() !== $confidentiality) {
                if ($confidentiality == ModelPage::CONFIDENTIALITY_PRIVATE) {
                    $this->getServicePost()->hardDelete('PP'.$id);
                } elseif ($confidentiality == ModelPage::CONFIDENTIALITY_PUBLIC) {
                    $this->getServicePost()->addSys(
                        'PP'.$id, '', [
                        'state' => 'create',
                        'user' => $tmp_m_post->getOwnerId(),
                        'org' => $tmp_m_post->getOrganizationId(),
                        'parent' => $tmp_m_post->getPageId(),
                        'page' => $id,
                        'type' => $tmp_m_post->getType(),
                        ], 'create', null/*sub*/, null/*parent*/,
                        $tmp_m_post->getPageId()/*page*/,
                        $tmp_m_post->getOrganizationId()/*org*/,
                        $tmp_m_post->getOwnerId()/*user*/, null/*course*/, 'page'
                    );
                }
            }
        }

        $this->getMapper()->update($this->getModel()->setConfidentiality($confidentiality), ['page_id' => $id]);
        return $this->getMapper()->update($m_page);
    }



    /**
     * Delete Page
     *
     * @invokable
     *
     * @param  int $id
     * @return int
     */
    public function delete($id)
    {
        if (!is_array($id)) {
            $id = [$id];
        }
        $m_page = $this->getModel()->setId($id)
            ->setDeletedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
        if ($this->getMapper()->update($m_page)) {
            foreach ($id as $i) {
                $this->getServicePost()->hardDelete('PP'.$i);
            }
            return true;
        }

        return false;
    }

      /**
     * Reactivate Page
     *
     * @invokable
     *
     * @param  int $id
     * @return int
     */
    public function reactivate($id)
    {
        $m_page = $this->getModel()->setId($id)->setDeletedDate(new \Zend\Db\Sql\Predicate\IsNull());

        return $this->getMapper()->update($m_page);
    }

    /**
     * Get Page
     *
     * @invokable
     *
     * @param int    $id
     * @param int    $parent_id
     * @param string $type
     */
    public function get($id = null, $parent_id = null, $type = null)
    {
        if (null === $id && null === $parent_id) {
            throw new \Exception('Error: params is null');
        }
        $identity = $this->getServiceUser()->getIdentity();
        $is_sadmin_admin = (in_array(ModelRole::ROLE_SADMIN_STR, $identity['roles']) || in_array(ModelRole::ROLE_ADMIN_STR, $identity['roles']));
        $res_page = $this->getMapper()->get($identity['id'], $id, $parent_id, $type, $is_sadmin_admin);
        if ($res_page->count() <= 0) {
            throw new \Exception('This page does not exist');
        }

        foreach ($res_page as $m_page) {
            $m_page->setTags($this->getServicePageTag()->getList($m_page->getId()));
            $m_page->setDocs($this->getServicePageDoc()->getList($m_page->getId()));
            $m_page->setUsers($this->getServicePageUser()->getList($m_page->getId(), null, $m_page->getRole()));
            $m_page->setEvents($this->getList(null, $m_page->getId(), null, null, ModelPage::TYPE_EVENT, null, null, null, null));
            $this->getOwner($m_page);
        }

        $res_page->rewind();

        return (is_array($id)) ? $res_page->toArray(['id']) : $res_page->current();
    }

    /**
     * Get Page Lite
     *
     * @invokable
     *
     * @param  int $id
     * @return \Application\Model\Page
     */
    public function getLite($id)
    {
        return $this->getMapper()->select($this->getModel()->setId($id))->current();
    }


    /**
     * Get Page
     *
     * @invokable
     *
     * @param int    $id
     * @param int    $parent_id
     * @param int    $user_id
     * @param int    $organization_id
     * @param string $type
     * @param string $start_date
     * @param string $end_date
     * @param int    $member_id
     * @param array  $filter
     * @param bool   $strict_dates
     * @param string $search
     * @param array  $tags
     *
     * @throws \Exception
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getList($id = null, $parent_id = null, $user_id = null, $organization_id = null, $type = null, $start_date = null, $end_date = null, $member_id = null, $filter = null, $strict_dates = false, $search = null, $tags = null)
    {
        if (empty($tags)) {
            $tags = null;
        }
        $identity = $this->getServiceUser()->getIdentity();
        $is_sadmin_admin = (in_array(ModelRole::ROLE_SADMIN_STR, $identity['roles']) || in_array(ModelRole::ROLE_ADMIN_STR, $identity['roles']));

        $mapper = $this->getMapper()->usePaginator($filter);
        $res_page = $mapper->getList($identity['id'], $id, $parent_id, $user_id, $organization_id, $type, $start_date, $end_date, $member_id, $strict_dates, $is_sadmin_admin, $search, $tags);

        foreach ($res_page as $m_page) {
            $m_page->setTags($this->getServicePageTag()->getList($m_page->getId()));
            $m_page->setDocs($this->getServicePageDoc()->getList($m_page->getId()));
            $m_page->setUsers($this->getServicePageUser()->getList($m_page->getId(), null, $m_page->getRole()));
            $this->getOwner($m_page);
        }

        return ['count' => $mapper->count(), 'list' => $res_page];
    }
    
      /**
     * Get Page
     *
     * @invokable
     *
     * @param int    $id
     * @param int    $parent_id
     * @param int    $user_id
     * @param int    $organization_id
     * @param string $type
     * @param string $start_date
     * @param string $end_date
     * @param int    $member_id
     * @param array  $filter
     * @param bool   $strict_dates
     * @param string $search
     * @param array  $tags
     *
     * @throws \Exception
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function m_getList($id = null, $parent_id = null, $user_id = null, $organization_id = null, $type = null, $start_date = null, $end_date = null, $member_id = null, $filter = null, $strict_dates = false, $search = null, $tags = null)
    {
        $ids = !is_array($id) ? [$id] : $id;
        if (empty($tags)) {
            $tags = null;
        }
        $identity = $this->getServiceUser()->getIdentity();
        $is_sadmin_admin = (in_array(ModelRole::ROLE_SADMIN_STR, $identity['roles']) || in_array(ModelRole::ROLE_ADMIN_STR, $identity['roles']));

        $res_page = $this->getMapper()->getList($identity['id'], $id, $parent_id, $user_id, $organization_id, $type, $start_date, $end_date, $member_id, $strict_dates, $is_sadmin_admin, $search, $tags);
        
        
        $pages = [];
        foreach ($ids as &$id) {
            $pages[$id] = [];
        }
        foreach ($res_page->toArray() as &$page) {
            $pages[$page['page_id']][] = $page['id'];
        }
        return $pages;

    }


    /**
    * Get owner string by Page Model
    *
    * @param \Application\Model\Page $m_page
    */
    private function getOwner(\Application\Model\Page $m_page)
    {
        $owner = [];
        switch (true) {
            case is_numeric($m_page->getPageId()):
                $ar_page = $m_page->getPage()->toArray();
                $owner = [
                    'id' => $ar_page['id'],
                    'text' => $ar_page['title'],
                    'img' => $ar_page['logo'],
                    'type' => 'page',
                ];
                break;
            case is_numeric($m_page->getOrganizationId()):
                $ar_organization = $m_page->getOrganization()->toArray();
                $owner = [
                    'id' => $ar_organization['id'],
                    'text' => $ar_organization['name'],
                    'img' => $ar_organization['logo'],
                    'type' => 'organization',
                ];
                break;
            case is_numeric($m_page->getOwnerId()):
                $ar_user = $m_page->getUser()->toArray();
                $owner = [
                    'id' => $ar_user['id'],
                    'text' => $ar_user['firstname'] . ' ' . $ar_user['lastname'],
                    'img' => $ar_user['avatar'],
                    'type' => 'user',
                ];
                break;

        }

        $m_page->setOwner($owner);
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
     * Get Service Post
     *
     * @return \Application\Service\Post
     */
    private function getServicePost()
    {
        return $this->container->get('app_service_post');
    }
}
