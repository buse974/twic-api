<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Page User
 */
namespace Application\Service;

use Dal\Service\AbstractService;
use Application\Model\PageUser as ModelPageUser;
use Application\Model\Page as ModelPage;
use Application\Model\Role as ModelRole;

/**
 * Class PageUser
 *
 *  @TODO Check vérification sécuriter des user page + parmas par default role use + exposer méthode spécifique pour l'acceptation
 */
class PageUser extends AbstractService
{

    /**
     * Add Page User Relation
     *
     * @invokable
     *
     * @param  int       $page_id
     * @param  int|array $user_id
     * @param  string    $role
     * @param  string    $state
     * @return int
     */
    public function add($page_id, $user_id, $role, $state)
    {
        if (!is_array($user_id)) {
            $user_id = [$user_id];
        }

        $m_page_user = $this->getModel()
            ->setPageId($page_id)
            ->setRole($role)
            ->setState($state);
        $ret = 0;

        $m_page = $this->getServicePage()->getLite($page_id);
        // ON MET LES USER DANS LA CONVERSATION SI ELLE EXISTE
        if ($state === ModelPageUser::STATE_MEMBER) {
            if (is_numeric($m_page->getConversationId())) {
                $this->getServiceConversationUser()->add($m_page->getConversationId(), $user_id);
            }
        }

        foreach ($user_id as $uid) {
            $ret +=  $this->getMapper()->insert($m_page_user->setUserId($uid));
            // inviter only event
            if ($state === ModelPageUser::STATE_INVITED) {
                $this->getServicePost()->addSys(
                    'PPM'.$page_id.'_'.$uid,
                    '',
                    [
                    'state' => 'invited',
                    'user' => $uid,
                    'page' => $page_id,
                    'type' => $m_page->getType(),
                    ],
                    'invited',
                    ['M'.$uid]/*sub*/,
                    null/*parent*/,
                    $page_id/*page*/,
                    null/*user*/,
                    'page'
                );
                //$gcm_notification = new GcmNotification();
                /*$gcm_notification->setTitle($name)
                    ->setSound("default")
                    ->setColor("#00A38B")
                    ->setBody('Sent you a connection request');*/
            /*    $this->getServiceFcm()->send(
                    $uid, [
                    'data' => [
                        'type' => 'userpage',
                        'data' => [
                            'state' => 'invited',
                            'page' => $page_id,
                        ],
                    ],
                  ] //, $gcm_notification
                );

*/
                // member only group
            } elseif ($state === ModelPageUser::STATE_MEMBER) {
                $identity = $this->getServiceUser()->getIdentity();
                $is_admin = (in_array(ModelRole::ROLE_ADMIN_STR, $identity['roles']));

                if (ModelPage::TYPE_ORGANIZATION === $m_page->getType() && $is_admin === false) {
                    $this->getServiceUser()->update($uid, null, null, null, null, null, null, null, null, null, $page_id);
                }

                $this->getServiceSubscription()->add('PP'.$page_id, $uid);
                // Si il n'est pas le propriétaire on lui envoie une notification
                if ($m_page->getUserId() !== $uid) {
                    if ($m_page->getConfidentiality() == ModelPage::CONFIDENTIALITY_PUBLIC && ModelPage::TYPE_ORGANIZATION !== $m_page->getType()) {
                        $this->getServicePost()->addSys(
                            'PPM'.$page_id.'_'.$uid,
                            '',
                            [
                            'state' => 'member',
                            'user' => $uid,
                            'page' => $page_id,
                            'type' => $m_page->getType(),
                          ],
                            'member',
                            ['M'.$uid, 'PU'.$uid]/*sub*/,
                            null/*parent*/,
                            $page_id/*page*/,
                            $uid/*user*/,
                            'page'
                        );
                    } else {
                        $this->getServicePost()->addSys(
                            'PPM'.$page_id.'_'.$uid,
                            '',
                            [
                            'state' => 'member',
                            'user' => $uid,
                            'page' => $page_id,
                            'type' => $m_page->getType(),
                          ],
                            'member',
                            ['M'.$uid]/*sub*/,
                            null/*parent*/,
                            $page_id/*page*/,
                            $uid/*user*/,
                            'page'
                        );
                    }
                    /*
                                        $this->getServiceFcm()->send(
                                            $uid, [
                                            'data' => [
                                                'type' => 'userpage',
                                                'data' => [
                                                    'state' => 'member',
                                                    'page' => $page_id,
                                                ],
                                            ],
                                          ]
                                        );
                                        */
                }
            } else {
                /*    $this->getServiceFcm()->send(
                        $uid, [
                        'data' => [
                            'type' => 'userpage',
                            'data' => [
                                'state' => 'pending',
                                'page' => $page_id,
                            ],
                        ],
                      ]
                    );
                    */
            }
        }

        return $ret;
    }


    /**
     * Update Page User Relation
     *
     * @invokable
     *
     * @param  int    $page_id
     * @param  int    $user_id
     * @param  string $role
     * @param  string $state
     * @return int
     */
    public function update($page_id, $user_id, $role, $state)
    {
        $m_page_user = $this->getMapper()->select($this->getModel()->setPageId($page_id)->setUserId($user_id))->current();
        // si on doit l'abonner
        if (ModelPageUser::STATE_MEMBER === $state) {

        // ON MET LES USER DANS LA CONVERSATION SI ELLE EXISTE
            $m_page = $this->getServicePage()->getLite($page_id);
            if (is_numeric($m_page->getConversationId())) {
                $this->getServiceConversationUser()->add($m_page->getConversationId(), $user_id);
            }

            if ($m_page_user->getState() === ModelPageUser::STATE_PENDING || $m_page_user->getState() === ModelPageUser::STATE_INVITED) {
                $this->getServiceSubscription()->add('PP'.$page_id, $user_id);
                if ($m_page->getConfidentiality() == ModelPage::CONFIDENTIALITY_PUBLIC) {
                    $this->getServicePost()->addSys(
                    'PPM'.$page_id.'_'.$user_id,
                    '',
                    [
                    'state' => 'member',
                    'user' => $user_id,
                    'page' => $page_id,
                    'type' => $m_page->getType(),
                    ],
                    'member',
                    ['M'.$user_id, 'PU'.$user_id]/*sub*/,
                    null/*parent*/,
                    null/*page*/,
                    $user_id/*user*/,
                    'page'
                );
                }
            }
        }
        /*
                $this->getServiceFcm()->send(
                    $user_id, [
                    'data' => [
                        'type' => 'userpage',
                        'data' => [
                            'state' => $state,
                            'page' => $page_id,
                        ],
                    ],
                  ]
                );
        */

        //si on veux modifier le dernier administrateur
        if ($m_page_user->getRole() == 'admin' && $role !== 'admin' && $role !== null) {
            $ar_pu = $this->getListByPage($page_id, 'admin');
            if (count($ar_pu[$page_id]) === 1 && in_array($user_id, $ar_pu[$page_id])) {
                throw new \Exception("On ne peut pas Modifier le dernier administrateur");
            }
        }

        $m_page_user = $this->getModel()
          ->setRole($role)
          ->setState($state);

        return $this->getMapper()->update($m_page_user, ['page_id' => $page_id, 'user_id' => $user_id]);
    }

    /**
     * Delete Page User Relation
     *
     * @invokable
     *
     * @param  int $page_id
     * @param  int $user_id
     * @return int
     */
    public function delete($page_id, $user_id)
    {
        $m_page_user = $this->getModel()
            ->setPageId($page_id)
            ->setUserId($user_id);

        //si on suprime le dernier administrateur
        $ar_pu = $this->getListByPage($page_id, 'admin');
        if (count($ar_pu[$page_id]) === 1 && in_array($user_id, $ar_pu[$page_id])) {
            throw new \Exception("On ne peut pas suprimer le dernier administrateur");
        }

        $ret =  $this->getMapper()->delete($m_page_user);

        if ($ret) {
            $this->getServicePost()->hardDelete('PPM'.$page_id.'_'.$user_id);

            // ON DELETE LES USER DANS LA CONVERSATION SI ELLE EXISTE
            $m_page = $this->getServicePage()->getLite($page_id);
            if (is_numeric($m_page->getConversationId())) {
                $this->getServiceConversationUser()->delete($m_page->getConversationId(), $user_id);
            }
        }

        return $ret;
    }

    /**
     * Get List Page User Relation
     *
     * @param int    $page_id
     * @param array  $filter
     * @param string $state
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getList($page_id, $filter = null, $role = null)
    {
        //@TODO Petit hack pour le filtre getList dans le mapper a optimisé
        if (null === $role) {
            $role = 'norole';
        }
        $mapper = $this->getMapper();
        $res = $mapper->usePaginator($filter)->getList($page_id, null, $role);

        return null !== $filter ?
        ['list' => $res,'count' => $mapper->count()] :
        $res;
    }

    /**
     * Get List userId by Page
     *
     * @invokable
     *
     * @param int|array $page_id
     * @param string $role
     * @param string $state
     */
    public function getListByPage($page_id, $role = null, $state = null)
    {
        if (!is_array($page_id)) {
            $page_id = [$page_id];
        }

        $ret = [];
        foreach ($page_id as $page) {
            $ret[$page] = [];
        }

        $res_page_user = $this->getMapper()->getList($page_id, null, $role, $state);
        foreach ($res_page_user as $m_page_user) {
            $ret[$m_page_user->getPageId()][] = $m_page_user->getUserId();
        }

        return $ret;
    }

    /**
     * Get List pageId by User
     *
     * @invokable
     *
     * @param int|array $user_id
     * @param string $role
     * @param string $state
     * @param string $type
     */
    public function getListByUser($user_id, $role = null, $state = null, $type = null)
    {
        $identity = $this->getServiceUser()->getIdentity();
        if (!is_array($user_id)) {
            $user_id = [$user_id];
        }

        $ret = [];
        foreach ($user_id as $user) {
            $ret[$user] = [];
        }

        $res_page_user = $this->getMapper()->getList(null, $user_id, $role, $state, $type, $identity['id']);
        foreach ($res_page_user as $m_page_user) {
            $ret[$m_page_user->getUserId()][] = $m_page_user->getPageId();
        }

        return $ret;
    }

    public function getRole($page_id)
    {
        $identity = $this->getServiceUser()->getIdentity();

        return $this->getMapper()->getList($page_id, $identity['id'])->current();
    }

    /**
     * Add Array
     *
     * @param  int   $page_id
     * @param  array $data
     * @return array
     */
    public function _add($page_id, $data)
    {
        $ret = [];
        foreach ($data as $ar_u) {
            $user_id = (isset($ar_u['user_id'])) ? $ar_u['user_id']:null;
            $role = (isset($ar_u['role'])) ? $ar_u['role']:null;
            $state = (isset($ar_u['state'])) ? $ar_u['state']:null;

            $ret[$user_id] = $this->add($page_id, $user_id, $role, $state);
        }

        return $ret;
    }

    /**
     * Add Array
     *
     * @param  int   $page_id
     * @param  array $data
     * @return array
     */
    public function replace($page_id, $data)
    {
        $this->getMapper()->delete($this->getModel()->setPageId($page_id));

        return $this->_add($page_id, $data);
    }

    /**
     * Get Service Subscription
     *
     * @return \Application\Service\Subscription
     */
    private function getServiceSubscription()
    {
        return $this->container->get('app_service_subscription');
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
     * Get Service Post
     *
     * @return \Application\Service\Page
     */
    private function getServicePage()
    {
        return $this->container->get('app_service_page');
    }

    /**
     * Get Service Conversation User
     *
     * @return \Application\Service\ConversationUser
     */
    private function getServiceConversationUser()
    {
        return $this->container->get('app_service_conversation_user');
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
     * Get Service Service Conversation User
     *
     * @return \Application\Service\Fcm
     */
  /*  private function getServiceFcm()
    {
        return $this->container->get('fcm');
    }*/
}
