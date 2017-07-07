<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Expression;

class Item extends AbstractMapper
{
  public function getListId($page_id, $me, $is_admin_page, $parent_id = null)
  {
    $select = $this->tableGateway->getSql()->select();
    $select->columns(['id', 'parent_id', 'page_id'])
      ->join('page_user', 'page_user.page_id=item.page_id', [])
      ->where(['page_user.user_id' => $me])
      ->where(['item.page_id' => $page_id])
      ->order('item.page_id ASC')
      ->order('item.order ASC');

    if(null !== $parent_id) {
      $select->where(['item.parent_id' => $parent_id]);
    } else {
      $select->where(['item.parent_id IS NULL']);
    }

    if($is_admin_page !== true) {
      $select->where(['item.is_published IS TRUE']);
    }

    return $this->selectWith($select);
  }

  public function getListAssignmentId($page_id, $me, $is_admin_page)
  {
    $select = $this->tableGateway->getSql()->select();
    $select->columns(['id', 'parent_id', 'page_id'])
      ->join('page_user', 'page_user.page_id=item.page_id', [])
      ->where(['page_user.user_id' => $me])
      ->where(['item.page_id' => $page_id])
      ->where(["( `item`.`type` IN ('A', 'QUIZ', 'DISC') OR ( `item`.`type` NOT IN ('A', 'QUIZ', 'DISC') AND `item`.`points` IS NOT NULL ) )"])
      ->order('item.page_id ASC')
      ->order('item.order ASC');

    if($is_admin_page !== true) {
      $select->where(['item.is_published IS TRUE']);
    }

    return $this->selectWith($select);
  }

  public function get($id, $me)
  {
      $select = $this->tableGateway->getSql()->select();
      $select->columns([
        'id',
        'title',
        'points',
        'description',
        'type',
        'is_available',
        'is_published',
        'order',
        'start_date',
        'end_date',
        'updated_date',
        'created_date',
        'parent_id',
        'page_id',
        'user_id',
        'participants',
        'item$library_id' => new Expression("IF(`page_user`.`role`='admin'OR(`item`.`is_available`=1 OR (`item`.`is_available` = 3 AND (( `item`.`start_date` IS NULL AND `item`.`end_date` IS NULL )OR( `item`.`start_date` < UTC_TIMESTAMP() AND `item`.`end_date` IS NULL )OR( `item`.`start_date` IS NULL AND `item`.`end_date` > UTC_TIMESTAMP())))), `item`.`library_id`, NULL)"),
        'item$post_id' =>    new Expression("IF(`page_user`.`role`='admin'OR(`item`.`is_available`=1 OR (`item`.`is_available` = 3 AND (( `item`.`start_date` IS NULL AND `item`.`end_date` IS NULL )OR( `item`.`start_date` < UTC_TIMESTAMP() AND `item`.`end_date` IS NULL )OR( `item`.`start_date` IS NULL AND `item`.`end_date` > UTC_TIMESTAMP())))), `post`.`id`, NULL)"),
        'item$quiz_id' =>    new Expression("IF(`page_user`.`role`='admin'OR(`item`.`is_available`=1 OR (`item`.`is_available` = 3 AND (( `item`.`start_date` IS NULL AND `item`.`end_date` IS NULL )OR( `item`.`start_date` < UTC_TIMESTAMP() AND `item`.`end_date` IS NULL )OR( `item`.`start_date` IS NULL AND `item`.`end_date` > UTC_TIMESTAMP())))), `quiz`.`id`, NULL)"),
        'item$text' =>       new Expression("IF(`page_user`.`role`='admin'OR(`item`.`is_available`=1 OR (`item`.`is_available` = 3 AND (( `item`.`start_date` IS NULL AND `item`.`end_date` IS NULL )OR( `item`.`start_date` < UTC_TIMESTAMP() AND `item`.`end_date` IS NULL )OR( `item`.`start_date` IS NULL AND `item`.`end_date` > UTC_TIMESTAMP())))), `item`.`text`, NULL)")
      ])
      ->join('page_user', 'page_user.page_id=item.page_id', [])
      ->join('post', 'item.id=post.item_id', [], $select::JOIN_LEFT)
      ->join('quiz', 'item.id=quiz.item_id', [], $select::JOIN_LEFT)
      ->where(['page_user.user_id' => $me])
      ->where(['item.id' => $id]);

    return $this->selectWith($select);
  }

  public function getInfo($id)
  {
    $select = $this->tableGateway->getSql()->select();
    $select->columns([
      'item$nb_total' => new Expression("IF(item.participants = 'ALL', COUNT(true), IF(item.participants = 'USER', COUNT(item_user.id), COUNT(DISTINCT item_user.group_id)))"),
      'item$nb_grade' => new Expression("IF(item.participants = 'GROUP', COUNT(DISTINCT item_user.group_id , IF(item_user.rate IS NULL, NULL, true))  , COUNT(item_user.rate))"),
      'item$nb_submission' => new Expression("IF(item.participants = 'GROUP', COUNT(DISTINCT item_user.group_id , IF(submission.submit_date IS NULL, NULL, true)) , COUNT(submission.submit_date))")
    ])
      ->join('page_user', 'page_user.page_id=item.page_id', [])
      ->join('item_user', 'item.id=item_user.item_id AND page_user.user_id=item_user.user_id', [], $select::JOIN_LEFT)
      ->join('submission', 'submission.id=item_user.submission_id', [], $select::JOIN_LEFT)
      ->where(['item.id' => $id]);

    return $this->selectWith($select);
  }

  public function getListSubmission($id)
  {
    $select = $this->tableGateway->getSql()->select();
    $select->columns(['id'])
      ->join('page_user', 'page_user.page_id=item.page_id', ['user_id'])
      ->join('item_user', 'item.id=item_user.item_id AND page_user.user_id=item_user.user_id', ['rate', 'group_id'], $select::JOIN_LEFT)
      ->join('submission', 'submission.id=item_user.submission_id', ['item_id', 'submit_date', 'post_id', 'is_graded'], $select::JOIN_LEFT)
      ->where(['item.id' => $id]);

    return $this->selectWith($select);
  }


  public function getLastOrder($id, $page_id, $parent_id = null)
  {
    $select = $this->tableGateway->getSql()->select();
    $select->columns(['order']);
    if(is_numeric($parent_id)) {
      $select->where(['item.parent_id' => $parent_id]);
    } else {
      $select->where(['item.parent_id IS NULL']);
    }

    $select->where(['item.page_id' => $page_id])->where(['item.id <> ?' => $id])
      ->order('order DESC')
      ->limit(1);

    return $this->selectWith($select);
  }

  public function uptOrder($page_id, $order, $parent_id)
  {
    $update = $this->tableGateway->getSql()->update();
    $update->set(['order' => new Expression('`item`.`order`+1')])
      ->where(['`item`.`order` >= ? ' => $order])
      ->where(['page_id' => $page_id]);

    if(is_numeric($parent_id)) {
      $update->where(['item.parent_id' => $parent_id]);
    } else {
      $update->where(['item.parent_id IS NULL']);
    }

    return $this->updateWith($update);
  }
}
