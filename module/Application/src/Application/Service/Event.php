<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Event
 */
namespace Application\Service;

use Dal\Service\AbstractService;
use Zend\Json\Server\Request;
use Zend\Http\Client;

/**
 * Class Event
 */
class Event extends AbstractService
{
    /**
     * Identification request.
     *
     * @var int
     */
    private static $id = 0;

    const TARGET_TYPE_USER = 'user';
    const TARGET_TYPE_GLOBAL = 'global';
    const TARGET_TYPE_SCHOOL = 'school';

    /**
     * create event
     *
     * @param  string $event
     * @param  mixed  $source
     * @param  mixed  $object
     * @param  array  $user
     * @param  mixed  $target
     * @param  mixed  $src
     * @throws \Exception
     * @return int
     */
    public function create($event, $source, $object, $libelle, $target, $src = null)
    {
        $date = (new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s');
        $m_event = $this->getModel()
            ->setUserId($src)
            ->setEvent($event)
            ->setSource(json_encode($source))
            ->setObject(json_encode($object))
            ->setTarget($target)
            ->setDate($date);

        if ($this->getMapper()->insert($m_event) <= 0) {
            throw new \Exception('error insert event');
        }
        $event_id = $this->getMapper()->getLastInsertValue();
        $this->getServiceEventSubscription()->add($libelle, $event_id);
        $user = $this->getServiceSubscription()->getListUserId($libelle);
        if (count($user) > 0) {
            $this->sendRequest(
                array_values($user), [
                'id' => $event_id,
                'event' => $event,
                'source' => $source,
                'date' => (new \DateTime($date))->format('Y-m-d\TH:i:s\Z'),
                'object' => $object], $target
            );
        }

        return $event_id;
    }

    /**
     * Send Request Event.
     *
     * @param array $users
     * @param array $notification
     * @param mixed $target
     *
     * @throws \Exception
     *
     * @return \Zend\Json\Server\Response
     */
    public function sendRequest($users, $notification, $target)
    { 
        $rep = false;
        $request = new Request();
        $request->setMethod('notification.publish')
            ->setParams(array('notification' => $notification,'users' => $users,'type' => $target))
            ->setId(++ self::$id)
            ->setVersion('2.0');

        $client = new \Zend\Json\Server\Client($this->container->get('config')['node']['addr'], $this->getClient());
        try {
            $rep = $client->doRequest($request);
            if ($rep->isError()) {
                throw new \Exception('Error jrpc nodeJs: ' . $rep->getError()->getMessage(), $rep->getError()->getCode());
            }
        } catch (\Exception $e) {
            syslog(1, 'Request: ' . $request->toJson());
            syslog(1, $e->getMessage());
        }

        return $rep;
    }

    /**
     * Check if User is connect.
     *
     * @param int $user
     *
     * @return array
     */
    public function isConnected($user)
    {
        $rep = false;
        $request = new Request();
        $request->setMethod('user.isConnected')
            ->setParams(array('user' => (int) $user))
            ->setId(++ self::$id)
            ->setVersion('2.0');

        $client = new \Zend\Json\Server\Client($this->container->get('config')['node']['addr'], $this->getClient());

        try {
            $rep = $client->doRequest($request)->getResult();
        } catch (\Exception $e) {
            syslog(1, $e->getMessage());
        }

        return $rep;
    }

    /**
     * Get Client Http.
     *
     * @return \Zend\Http\Client
     */
    private function getClient()
    {
        $client = new Client();
        $client->setOptions($this->container->get('config')['http-adapter']);

        return $client;
    }

    /**
     * Get List Event.
     *
     * @invokable
     *
     * @param array  $filter
     * @param string $events
     * @param int    $user
     * @param int    $id
     * @param int    $source
     *
     * @return array
     */
    public function getList($filter = null, $events = null, $user = null, $id = null, $source = null)
    {
        $mapper = $this->getMapper();
        if (null === $user) {
            $user = $this->getServiceUser()->getIdentity()['id'];
        }

        $res_event = $mapper->usePaginator($filter)->getList($user, $events, $id, $source);
        $count = $mapper->count();

        $ar_event = $res_event->toArray();
        foreach ($ar_event as &$event) {
            $event['source'] = json_decode($event['source'], true);
            $event['object'] = json_decode($event['object'], true);
            ;
        }

        return ['list' => $ar_event,'count' => $count];
    }

    /**
     * Get Event.
     *
     * @param int $id
     *
     * @return \Application\Model\Event
     */
    public function get($id)
    {
        $m_event = $this->getMapper()
            ->getList(
                $this->getServiceUser()
                    ->getIdentity()['id'], null, $id
            )
            ->current();

        return $m_event;
    }


    // /////////////// EVENT //////////////////////

    /**
     * Event user.publication
     *
     * @param  int   $post_id
     * @param  array $sub
     * @return number
     */
    public function userPublication($sub, $post_id, $type = null, $ev = null)
    {
        $user_id = $this->getServiceUser()->getIdentity()['id'];
        $data_post = $this->getDataPost($post_id);

        if (null === $type) {
            $event = 'user.publication';
        } else {
            $event = $type;
            if (is_string($ev)) {
                $event .= '.'.$ev;
            }
        }

        return $this->create($event, $this->getDataUser(), $data_post, $sub, self::TARGET_TYPE_USER, $user_id);
    }

    /**
     * Event message.new
     *
     * @param int   $message_id
     * @param array $to
     *
     * @return int
     */
    public function messageNew($message_id, $to)
    {
        if (!is_array($to)) {
            $to = [$to];
        }
        foreach ($to as $tt) {
            $ttto[] = 'M'.$tt;
        }
        $from = $this->getDataUser();
        $ret = $this->create('message.new', $from, $this->getDataMessage($message_id), $ttto, self::TARGET_TYPE_USER, $this->getServiceUser()->getIdentity()['id']);

        foreach ($to as $t) {
            $u = $this->getDataUser($t);
            //if (!$this->isConnected($t)  $u['data']['has_email_notifier'] == true) {
            if ($u['data']['has_email_notifier'] == true) {
                try {
                    $this->getServiceMail()->sendTpl('tpl_newmessage', $u['data']['email'], array('to_firstname' => $u['data']['firstname'],'to_lastname' => $u['data']['lastname'],'to_avatar' => $u['data']['avatar'],'from_firstname' => $from['data']['firstname'],'from_lastname' => $from['data']['lastname'],'from_avatar' => $from['data']['avatar']));
                } catch (\Exception $e) {
                    syslog(1, 'Model tpl_newmessage does not exist');
                }
            }
        }

        return $ret;
    }



















    // ------------- DATA OBJECT -------------------

    /**
     * Get Data Post
     *
     * @param  int $post_id
     * @return array
     */
    private function getDataPost($post_id)
    {
        $ar_post = $this->getServicePost()->getLite($post_id)->toArray();

        return [
            'id' => $ar_post['id'],
            'name' => 'post',
            'data' => [
                'id' =>  $ar_post['id'],
                'content' => $ar_post['content'],
                'picture' => $ar_post['picture'],
                'name_picture' => $ar_post['name_picture'],
                'link' => $ar_post['link'],
                't_page_id' => $ar_post['t_page_id'],
                't_user_id' => $ar_post['t_user_id'],
                'parent_id' => $ar_post['parent_id'],
                'origin_id' => $ar_post['origin_id'],
                'type' => $ar_post['type'],
            ]
        ];
    }

    /**
     * Get Data User.
     *
     * @param int $user_id
     *
     * @return array
     */
    private function getDataUser($user_id = null)
    {
        if (null == $user_id) {
            $identity = $this->getServiceUser()->getIdentity();
            if ($identity === null) {
                return [];
            }
            $user_id = $identity['id'];
        }

        $m_user = $this->getServiceUser()->get($user_id);

        return ['id' => $user_id,
            'name' => 'user','data' =>
            ['firstname' => $m_user['firstname'],'email' => $m_user['email'],'lastname' => $m_user['lastname'],'nickname' => $m_user['nickname'],'gender' =>
                $m_user['gender'],
                'has_email_notifier' => $m_user['has_email_notifier'],
                'avatar' => $m_user['avatar'],
                'organization' => $m_user['organization_id'],
                'user_roles' => $m_user['roles']]];
    }

    /**
     * Get Data Message.
     *
     * @param int $message_id
     *
     * @return array
     */
    private function getDataMessage($message_id)
    {
        $m_message = $this->getServiceMessageUser()
            ->getMessage($message_id)
            ->getMessage();

        return ['id' => $m_message->getId(),'name' => 'message','data' => $m_message];
    }




// ----------------------------- Service
    /**
     * Get Service Event Comment.
     *
     * @return \Application\Service\EventSubscription
     */
    private function getServiceEventSubscription()
    {
        return $this->container->get('app_service_event_subscription');
    }

    /**
     * Get Service User.
     *
     * @return \Application\Service\User
     */
    private function getServiceUser()
    {
        return $this->container->get('app_service_user');
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
     * Get Service Subscription
     *
     * @return \Application\Service\Subscription
     */
    private function getServiceSubscription()
    {
        return $this->container->get('app_service_subscription');
    }

    /**
     * Get Service Mail.
     *
     * @return \Mail\Service\Mail
     */
    private function getServiceMail()
    {
        return $this->container->get('mail.service');
    }

    /**
     * Get Service Message User.
     *
     * @return \Application\Service\MessageUser
     */
    private function getServiceMessageUser()
    {
        return $this->container->get('app_service_message_user');
    }

}
