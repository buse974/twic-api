<?php
/**
 * TheStudnet (http://thestudnet.com)
 *
 * User
 */
namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\Predicate;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate\NotIn;
use Zend\Db\Sql\Where;

/**
 * Class  User
 */
class User extends AbstractMapper
{

    public function get($user_id, $me, $is_sadmin_admin = false)
    {
        $columns = array(
            'user$id' => new Expression('user.id'),
            'firstname',
            'gender',
            'lastname',
            'nickname',
            'email',
            'background',
            'has_email_notifier',
            'user$birth_date' => new Expression('DATE_FORMAT(user.birth_date, "%Y-%m-%dT%TZ")'),
            'position',
            'interest',
            'avatar',
            'organization_id',
            'ambassador',
            'user$contacts_count' => $this->getSelectContactCount(),
            'user$contact_state' => $this->getSelectContactState($me), );

        $select = $this->tableGateway->getSql()->select();
        $select->columns($columns)
            ->join(array('nationality' => 'country'), 'nationality.id=user.nationality', array('id', 'short_name'), $select::JOIN_LEFT)
            ->join(array('origin' => 'country'), 'origin.id=user.origin', array('id', 'short_name'), $select::JOIN_LEFT)
            ->where(['user.id' => $user_id])
            ->quantifier('DISTINCT');

        /*if ($is_sadmin_admin === false && $user_id !== $me) {
            $select->join(['co' => 'circle_organization'], 'co.organization_id=user.organization_id', [])
                ->join('circle_organization', 'circle_organization.circle_id=co.circle_id', [])
                ->join(['circle_page_user' => 'page_user'], 'circle_page_user.page_id=circle_organization.organization_id', [])
                ->where(['circle_page_user.user_id' => $user_id]);
        }*/
        return $this->selectWith($select);
    }

    public function getListLite($id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'firstname', 'lastname', 'nickname', 'avatar'))->where(array('user.id' => $id));

        return $this->selectWith($select);
    }

    public function getList(
      $user_id,
      $is_admin,
      $filter = null,
      $post = null,
      $type = null,
      $search = null,
      $organization_id = null,
      $order = null,
      array $exclude = null,
      $contact_state = null)
    {
        $select = $this->tableGateway->getSql()->select();
        if ($is_admin) {
            $select->columns([
              'user$id' => new Expression('user.id'),
              'firstname', 'lastname', 'email', 'nickname', 'ambassador', 'email_sent',
              'user$birth_date' => new Expression('DATE_FORMAT(user.birth_date, "%Y-%m-%dT%TZ")'),
              'position', 'interest', 'avatar', 'suspension_date', 'suspension_reason',
              'user$contact_state' => $this->getSelectContactState($user_id),
              'user$contacts_count' => $this->getSelectContactCount()
            ]);
        } else {
            $select->columns([
              'user$id' => new Expression('user.id'),
              'firstname', 'lastname', 'email', 'nickname','ambassador',
              'user$birth_date' => new Expression('DATE_FORMAT(user.birth_date, "%Y-%m-%dT%TZ")'),
              'position', 'interest', 'avatar',
              'user$contact_state' => $this->getSelectContactState($user_id),
              'user$contacts_count' => $this->getSelectContactCount()
            ])->join(['co' => 'circle_organization'], 'co.organization_id=user.organization_id', [])
             ->join('circle_organization', 'circle_organization.circle_id=co.circle_id', [])
             ->join('page_user', 'page_user.page_id=circle_organization.organization_id', [])
             ->where(['page_user.user_id' => $user_id]);
        }
        $select->where('user.deleted_date IS NULL')
            ->order(['user.id' => 'DESC'])
            ->group('user.id')
            ->quantifier('DISTINCT');

        switch ($order) {
          case 'firstname':
              $select->order('user.firstname ASC');
              break;
          case 'random':
              $select->order(new Expression('RAND()'));
              break;
        }
        if ($exclude) {
            $select->where->notIn('user.id', $exclude);
        }
        if (null !== $post) {
            $select->join('post_like', 'post_like.user_id=user.id', array())
                ->where(array('post_like.post_id' => $post))
                ->where(array('post_like.is_like IS TRUE'));
        }
        if (null !== $organization_id) {
            $select->where(array('user.organization_id' => $organization_id));
        }
        if (null !== $search) {
            $select->where(array('CONCAT_WS(" ", user.firstname, user.lastname) LIKE ? ' => ''.$search.'%'))
                ->where(array('CONCAT_WS(" ", user.lastname, user.firstname) LIKE ? ' => ''.$search.'%'), Predicate::OP_OR)
                ->where(array('user.nickname LIKE ? )' => ''.$search.'%'), Predicate::OP_OR);
        }
        if (null !== $contact_state) {
            if (!is_array($contact_state)) {
                $contact_state = [$contact_state];
            }
            $select->having(['user$contact_state' => $contact_state]);
            if (in_array(0, $contact_state)) {
                $select->having('user$contact_state IS NULL', Predicate::OP_OR);
            }
        }

	       return $this->selectWith($select);
    }

    public function getListContact($me, $type = null, $date = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(
            array('id',
            'firstname',
            'lastname',
            'organization_id', 'email', 'nickname',
            'user$birth_date' => new Expression('DATE_FORMAT(user.birth_date, "%Y-%m-%dT%TZ")'),
            'position',
            'interest',
            'avatar', )
        )
            ->join('contact', 'contact.contact_id=user.id', array('request_date', 'accepted_date', 'deleted_date', 'requested', 'accepted', 'deleted'))
            ->where('user.deleted_date IS NULL')
            ->where(array('contact.user_id' => $me))
            ->order(array('user.id' => 'DESC'))
            ->quantifier('DISTINCT');

        switch ($type) {
        case 1: // on me demande en contact
            $select->where(array('contact.request_date IS NOT NULL AND contact.accepted_date IS NULL AND contact.deleted_date IS NULL AND requested IS false AND accepted IS false AND deleted IS false'));
            break;
        case 2: // j'ai demander en contact
            $select->where(array('contact.request_date IS NOT NULL AND contact.accepted_date IS NULL AND contact.deleted_date IS NULL AND requested IS true AND accepted IS false AND deleted IS false'));
            break;
        case 3: // on ma refuser en contact
            $select->where(array('contact.request_date IS NOT NULL AND contact.accepted_date IS NULL AND contact.deleted_date IS NOT NULL AND requested IS true AND accepted IS false AND deleted IS false'));
            break;
        case 4: // on ma suprimé alors que je suis en contact
            $select->where(array('contact.request_date IS NOT NULL AND contact.accepted_date IS NOT NULL AND contact.deleted_date IS NOT NULL AND deleted IS false'));
            break;
        case 5: // contact ok
            $select->where(array('contact.accepted_date IS NOT NULL AND contact.deleted_date IS NULL'));
            break;
        }

        if ($date) {
            $select->where(array('( contact.request_date < ? ' => $date, ' contact.accepted_date < ? ' => $date, ' contact.deleted_date < ? ) ' => $date));
        }

        return $this->selectWith($select);
    }

    public function getEmailUnique($email, $user)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('user$nb_user' => new Expression('COUNT(true)')))
            ->where(array('user.email' => $email))
            ->where(array('user.deleted_date IS NULL'));

        if (null !== $user) {
            $select->where(array('user.id <> ?' => $user));
        }

        return $this->selectWith($select);
    }

    public function getNbrSisUnique($sis, $user)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('user$nb_user' => new Expression('COUNT(true)')))
            ->where(array('user.sis' => $sis))
            ->where(array('user.deleted_date IS NULL'))
            ->where(array('user.sis IS NOT NULL'))
            ->where(array('user.sis <> ""'));

        if (null !== $user) {
            $select->where(array('user.id <> ?' => $user));
        }

        return $this->selectWith($select);
    }

    /**
     * Get Select Objet for Contact State
     *
     * @param  int $user
     * @return \Zend\Db\Sql\Select
     */
    public function getSelectContactState($user)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(
            array('user$contact_state' => new Expression(
                'IF(contact.accepted_date IS NOT NULL AND contact.deleted_date IS NULL, 3,
	         IF(contact.request_date IS NOT  NULL AND contact.requested <> 1 AND contact.deleted_date IS NULL, 2,
		     IF(contact.request_date IS NOT  NULL AND contact.requested = 1 AND contact.deleted_date IS NULL, 1,0)))'
            ))
        )
            ->join('contact', 'contact.contact_id = user.id', array())
            ->where(array('user.id=`user$id`'))
            ->where(['contact.user_id' => $user]);

        return $select;
    }

    /**
     * Get Select Objet for Contact Count
     *
     * @return \Zend\Db\Sql\Select
     */
    public function getSelectContactCount()
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('user$contacts_count' => new Expression('COUNT(1)')))
            ->join('contact', 'contact.contact_id = user.id', [])
            ->where(array('contact.user_id = `user$id` AND user.deleted_date IS NULL AND contact.accepted_date IS NOT NULL AND contact.deleted_date IS NULL'));

        return $select;
    }
}
