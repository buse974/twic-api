<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Feed Comment
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class FeedComment.
 */
class FeedComment extends AbstractService
{
    /**
     * Add Feed Comment.
     *
     * @param string $content
     * @param int    $feed_id
     *
     * @return int
     */
    public function add($content, $feed_id)
    {
        $user = $this->getServiceUser()->getIdentity()['id'];

        $m_feed = $this->getModel()
            ->setContent($content)
            ->setUserId($user)
            ->setFeedId($feed_id)
            ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));

        if ($this->getMapper()->insert($m_feed) <= 0) {
            new \Exception('error insert feed comment');
        }

        return $this->getMapper()->getLastInsertValue();
    }

    /**
     * Delete Feed Comment.
     *
     * @param int $id
     *
     * @return int
     */
    public function delete($id)
    {
        $user = $this->getServiceUser()->getIdentity()['id'];

        $m_feed_comment = $this->getModel()->setDeletedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));

        return $this->getMapper()->update($m_feed_comment, array('user_id' => $user, 'id' => $id));
    }

    /**
     * Get List Feed Comment.
     *
     * @param int $id
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getList($id)
    {
        return $this->getMapper()->getList($id);
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
}
