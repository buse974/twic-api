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
use ZendService\Google\Gcm\Notification as GcmNotification;

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
     * @param  strung    $state
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
        foreach ($user_id as $uid) {
            $ret +=  $this->getMapper()->insert($m_page_user->setUserId($uid));
            // inviter only event
            $m_page = $this->getServicePage()->getLite($page_id);
            if ($state === ModelPageUser::STATE_INVITED) {
                $this->getServicePost()->addSys(
                    'PPM'.$page_id.'_'.$uid, '', [
                    'state' => 'invited',
                    'user' => $uid,
                    'page' => $page_id,
                    'type' => $m_page->getType(),
                    ], 'invited', ['M'.$uid]/*sub*/, null/*parent*/, $page_id/*page*/, null/*user*/, null/*course*/, 'page'
                );

                //$gcm_notification = new GcmNotification();
                /*$gcm_notification->setTitle($name)
                    ->setSound("default")
                    ->setColor("#00A38B")
                    ->setBody('Sent you a connection request');*/
                $this->getServiceFcm()->send(
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


                // member only group
            } elseif ($state === ModelPageUser::STATE_MEMBER) {
                $this->getServiceSubscription()->add('PP'.$page_id, $uid);
                // Si il n'est pas le propriétaire on lui envoie une notification
                if ($m_page->getUserId() !== $uid) {
                    if ($m_page->getConfidentiality() == ModelPage::CONFIDENTIALITY_PUBLIC) {
                        $this->getServicePost()->addSys(
                            'PPM'.$page_id.'_'.$uid, '', [
                            'state' => 'member',
                            'user' => $uid,
                            'page' => $page_id,
                            'type' => $m_page->getType(),
                          ], 'member', ['M'.$uid, 'PU'.$uid]/*sub*/, null/*parent*/, $page_id/*page*/, $uid/*user*/, null/*course*/, 'page'
                        );
                    } else {
                        $this->getServicePost()->addSys(
                            'PPM'.$page_id.'_'.$uid, '', [
                            'state' => 'member',
                            'user' => $uid,
                            'page' => $page_id,
                            'type' => $m_page->getType(),
                          ], 'member', ['M'.$uid]/*sub*/, null/*parent*/, $page_id/*page*/, $uid/*user*/, null/*course*/, 'page'
                        );
                    }

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
                }
            } else {
              $this->getServiceFcm()->send(
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
     * @param  strung $state
     * @return int
     */
    public function update($page_id, $user_id, $role, $state)
    {
        // si on doit labonner
        if (ModelPageUser::STATE_MEMBER === $state) {
            $m_page_user = $this->getMapper()->select($this->getModel()->setPageId($page_id)->setUserId($user_id))->current();
            if ($m_page_user->getState() === ModelPageUser::STATE_PENDING || $m_page_user->getState() === ModelPageUser::STATE_INVITED) {
                $this->getServiceSubscription()->add('PP'.$page_id, $user_id);

                $m_page = $this->getServicePage()->getLite($page_id);
                if ($m_page->getConfidentiality() == ModelPage::CONFIDENTIALITY_PUBLIC) {
                    $this->getServicePost()->addSys(
                        'PPM'.$page_id.'_'.$user_id, '', [
                        'state' => 'member',
                        'user' => $user_id,
                        'page' => $page_id,
                        'type' => $m_page->getType(),
                        ], 'member', ['M'.$user_id, 'PU'.$user_id]/*sub*/, null/*parent*/, null/*page*/, $user_id/*user*/, null/*course*/, 'page'
                    );
                }
            }
        }

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

        $ret =  $this->getMapper()->delete($m_page_user);
        if ($ret) {
            $this->getServicePost()->hardDelete('PPM'.$page_id.'_'.$user_id);
        }

        return $ret;
    }

    /**
     * Get List Page User Relation
     *
     * @invokable
     *
     * @param int    $page_id
     * @param array  $filter
     * @param string $state

     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getList($page_id, $filter = null, $role = null)
    {
      //@TODO Petit hack pour le filtre getList dans le mapper a optimisé
      if(null === $role) {
        $role = 'norole';
      }
      $mapper = $this->getMapper();
      $res = $mapper->usePaginator($filter)->getList($page_id, $role);

      return null !== $filter ? ['list' => $res,'count' => $mapper->count()] : $res;
    }

   /**
     * Get List pageId by User and state
     *
     * @invokable
     *
     * @param array $users
     * @param string $state
     **/
    public function _getListByUser($users, $state)
    {
      $result = $this->getMapper()->getList(null, null, $users, $state);
      $ret = [];
      foreach ($result as $m_page_user) {
         $ret[$m_page_user->getUserId()][] = $m_page_user->getPageId();
      }

      foreach ($users as $user_id) {
        if(!isset($ret[$user_id])) {
          $ret[$user_id] = [];
        }
      }

      return $ret;
    }

     /**
     * Get List pageId by User and state
     *
     * @invokable
     *
     * @param array $pages
     * @param string $state
     **/
    public function m_getListByPage($pages, $role = null, $state = null)
    {
        $result = $this->getMapper()->m_getList($pages, $role, null, $state);
        $ret = [];
        foreach ($result as $m_page_user) {
           $ret[$m_page_user->getPageId()][] = $m_page_user->getUserId();
        }

        foreach ($pages as $page_id) {
            if(!isset($ret[$page_id])) {
                $ret[$page_id] = [];
            }
        }

        return $ret;
    }


   /**
    * Get List MEMBER pageId by User
    *
    * @invokable
    *
    * @param array $users
    */
    public function m_getListByUser($users)
    {
    return $this->_getListByUser($users, ModelPageUser::STATE_MEMBER);
    }

    /**
     * Get List INVITED pageId by User
     *
     * @invokable
     *
     * @param array $users
     */
     public function m_getInvitationListByUser($users)
     {
       return $this->_getListByUser($users, ModelPageUser::STATE_INVITED);
     }

     /**
      * Get List PENDING pageId by User
      *
      * @invokable
      *
      * @param array $users
      */
      public function m_getApplicationListByUser($users)
      {
        return $this->_getListByUser($users, ModelPageUser::STATE_PENDING);
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
     * Get Service Service Conversation User
     *
     * @return \Application\Service\Fcm
     */
    private function getServiceFcm()
    {
        return $this->container->get('fcm');
    }
}
