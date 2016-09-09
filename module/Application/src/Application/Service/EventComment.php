<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Event Comment
 */
namespace Application\Service;

use Dal\Service\AbstractService;
use Zend\Db\Sql\Predicate\IsNull;
use Application\Model\Role as ModelRole;

/**
 * Class Event Comment.
 */
class EventComment extends AbstractService
{
    /**
     * Add Event Comment.
     *
     * @invokable
     *
     * @param int    $comment
     * @param string $content
     *
     * @return int
     */
    public function add($event, $content)
    {
        $me = $this->getServiceUser()->getIdentity()['id'];

        $m_comment = $this->getModel()
            ->setEventId($event)
            ->setUserId($me)
            ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'))
            ->setContent($content);

        $this->getMapper()->insert($m_comment);

        $id = $this->getMapper()->getLastInsertValue();

        $this->getServiceEvent()->userComment($m_comment->setId($id));

        return $id;
    }

     /**
     * Add Event Comment.
     *
     * @invokable
     *
     * @param int    $id
     *
     * @return int
     */
    public function get($id)
    {
        return $this->getMapper()->get($id)->current();
    }
    
    /**
     * Update Event Comment.
     *
     * @invokable
     *
     * @param int    $comment
     * @param string $content
     *
     * @return int
     */
    public function update($comment, $content)
    {
        $me = $this->getServiceUser()->getIdentity()['id'];

        return $this->getMapper()->update($this->getModel()
            ->setId($comment)
            ->setContent($content), ['user_id' => $me, 'id' => $comment]);
    }

    /**
     * Delete Event Comment.
     *
     * @invokable
     *
     * @param int $comment
     *
     * @return int
     */
    public function delete($comment)
    {
         $identity = $this->getServiceUser()->getIdentity();
        if(!in_array(ModelRole::ROLE_SADMIN_STR, $identity['roles'])){
            return $this->getMapper()->update($this->getModel()
                ->setId($comment)
                ->setDeletedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s')), ['user_id' => $identity['id'], 'id' => $comment]);
        }
        else{
            return $this->getMapper()->update($this->getModel()
                ->setId($comment)
                ->setDeletedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s')), [ 'id' => $comment]);
        }
    }
    
     /**
     * Delete Event Comment.
     *
     * @invokable
     *
     * @param int $comment
     *
     * @return int
     */
    public function reactivate($comment)
    {
            return $this->getMapper()->update($this->getModel()
                ->setId($comment)
                ->setDeletedDate(new IsNull()), ['id' => $comment]);
     }
    

    /**
     * Get List Event.
     *
     * @invokable
     *
     * @param int $event
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getList($event)
    {
        return $this->getMapper()->getList($event);
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
     * Get Service User.
     *
     * @return \Application\Service\User
     */
    private function getServiceUser()
    {
        return $this->container->get('app_service_user');
    }
}
