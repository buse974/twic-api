<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Feed
 */
namespace Application\Service;

use Dal\Service\AbstractService;
use Zend\Http\Client;
use Application\Model\Feed as ModelFeed;
use Application\Model\Role as ModelRole;
use Zend\Db\Sql\Predicate\IsNull;

/**
 * Class Feed.
 */
class Feed extends AbstractService
{
    /**
     * Add feed.
     *
     * @invokable
     *
     * @param string $content
     * @param string $link
     * @param string $video
     * @param string $picture
     * @param string $document
     * @param string $name_picture
     * @param string $name_document
     * @param string $link_desc
     * @param string $link_title
     * @param string $type
     *
     * @return int
     */
    public function add($content = null, $link = null, $video = null, $picture = null, $document = null, $name_picture = null,
        $name_document = null, $link_desc = null, $link_title = null, $type = null)
    {
        $user = $this->getServiceUser()->getIdentity()['id'];

        $m_feed = $this->getModel()
            ->setContent($content)
            ->setUserId($user)
            ->setLink($link)
            ->setVideo($video)
            ->setPicture($picture)
            ->setLinkTitle($link_title)
            ->setLinkDesc($link_desc)
            ->setDocument($document)
            ->setNamePicture($name_picture)
            ->setNameDocument($name_document)
            ->setType($type)
            ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));

        if ($this->getMapper()->insert($m_feed) <= 0) {
            throw new \Exception('error insert feed');
        }

        $feed_id = $this->getMapper()->getLastInsertValue();

        if ($type === ModelFeed::TYPE_ACADEMIC) {
            $this->getServiceEvent()->userAnnouncement($feed_id);
        } else {
            $this->getServiceEvent()->userPublication($feed_id);
        }

        return $feed_id;
    }

    /**
     * Update feed.
     *
     * @invokable
     *
     * @param int    $id
     * @param string $content
     * @param string $link
     * @param string $video
     * @param string $picture
     * @param string $document
     * @param string $name_picture
     * @param string $name_document
     * @param string $link_desc
     * @param string $link_title
     *
     * @return int
     */
    public function update($id, $content = null, $link = null, $video = null, $picture = null, $document = null, $name_picture = null, $name_document = null, $link_desc = null, $link_title = null)
    {
        $user = $this->getServiceUser()->getIdentity()['id'];

        $m_feed = $this->getModel()
            ->setContent($content)
            ->setLink($link)
            ->setVideo($video)
            ->setPicture($picture)
            ->setLinkTitle($link_title)
            ->setLinkDesc($link_desc)
            ->setNamePicture($name_picture)
            ->setNameDocument($name_document)
            ->setDocument($document);

        return $this->getMapper()->update($m_feed, array('user_id' => $user, 'id' => $id));
    }

    /**
     * Delete Feed.
     *
     * @invokable
     *
     * @param int $id
     *
     * @return int
     */
    public function delete($id)
    {
        $identity = $this->getServiceUser()->getIdentity();
        $m_feed = $this->getModel()->setDeletedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
        if (!in_array(ModelRole::ROLE_SADMIN_STR, $identity['roles']) && !in_array(ModelRole::ROLE_ADMIN_STR, $identity['roles'])) {
            return $this->getMapper()->update($m_feed, array('user_id' => $identity['id'], 'id' => $id));
        } else {
            return $this->getMapper()->update($m_feed, array('id' => $id));
        }
    }


    /**
     * Reactivate Feed.
     *
     * @param int $id
     *
     * @return int
     */
    public function reactivate($id)
    {
        return $this->getMapper()->update($this->getModel()->setId($id)->setDeletedDate(new IsNull()));
    }

    /**
     * Add Comment Feed.
     *
     * @invokable
     *
     * @param int    $id
     * @param string $content
     *
     * @return int
     */
    public function addComment($id, $content)
    {
        return $this->getServiceFeedComment()->add($content, $id);
    }

    /**
     * Delete Comment Feed.
     *
     * @invokable
     *
     * @param int $id
     *
     * @return int
     */
    public function deleteComment($id)
    {
        return $this->getServiceFeedComment()->delete($id);
    }

    /**
     * Get List Comment Feed.
     *
     * @invokable
     *
     * @param int $id
     */
    public function GetListComment($id)
    {
        return $this->getServiceFeedComment()->getList($id);
    }

    /**
     * GetList Feed.
     *
     * @invokable
     *
     * @param string $filter
     * @param string $ids
     * @param int    $user
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getList($filter = null, $ids = null, $user = null)
    {
        $identity = $this->getServiceUser()->getIdentity();
        $me = $identity['id'];
        $res_contact = $this->getServiceContact()->getList();

        $mapper = $this->getMapper();
        if (null === $user) {
            $user = [$me];
            foreach ($res_contact as $m_contact) {
                $user[] = $m_contact->getContact()['id'];
            }
        }

        //$mapper = $mapper->usePaginator($filter);
        $is_sadmin = in_array(ModelRole::ROLE_SADMIN_STR, $identity['roles']) || in_array(ModelRole::ROLE_ADMIN_STR, $identity['roles']);
        return $mapper->getList($user, $me, $ids, $is_sadmin); //array('list' => $mapper->getList($user,$me, $ids), 'count' => $mapper->count());
    }

    /**
     * Get Feed.
     *
     * @param int $id
     *
     * @return \Application\Model\Feed|null
     */
    public function get($id)
    {
        $me = $this->getServiceUser()->getIdentity()['id'];

        return $this->getMapper()->getList(null, $me, $id)->current();
    }

    /**
     * Get preview Crawler.
     *
     * @invokable
     *
     * @param string $url
     *
     * @return array
     */
    public function linkPreview($url)
    {
        $client = new Client();
        $client->setOptions($this->container->get('Config')['http-adapter']);

        $pc = $this->getServiceSimplePageCrawler();
        $page = $pc->setHttpClient($client)->get($url);

        $return = $page->getMeta()->toArray();
        $return['images'] = $page->getImages()->getImages();
        if (isset($return['meta'])) {
            foreach ($return['meta'] as &$v) {
                $v = html_entity_decode(html_entity_decode($v));
            }
        }
        if (isset($return['open_graph'])) {
            foreach ($return['open_graph'] as &$v) {
                $v = html_entity_decode(html_entity_decode($v));
            }
        }

        return $return;
    }

    /**
     * Get Service FeedComment.
     *
     * @return \Application\Service\FeedComment
     */
    private function getServiceFeedComment()
    {
        return $this->container->get('app_service_feed_comment');
    }

    /**
     * Get Service Contact.
     *
     * @return \Application\Service\Contact
     */
    private function getServiceContact()
    {
        return $this->container->get('app_service_contact');
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
     * Get Service PageCrawler.
     *
     * @return \SimplePageCrawler\PageCrawler
     */
    private function getServiceSimplePageCrawler()
    {
        return $this->container->get('SimplePageCrawler');
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
