<?php

namespace Application\Model;

use Application\Model\Base\Item as BaseItem;

class Item extends BaseItem
{
  const IS_AVAILABLE_ON = 1;
  const IS_AVAILABLE_OFF = 2;
  const IS_AVAILABLE_AUTO = 3;

  const TYPE_SECTION = 'SCT';
  const TYPE_LIVE_CLASS = 'LC';
  const TYPE_ASSIGNMENT = 'A';
  const TYPE_QUIZ = 'QUIZ';
  const TYPE_PAGE = 'PG';
  const TYPE_DISCUSSION = 'DISC';
  const TYPE_MEDIA = 'MEDIA';

    protected $post_id;

    /**
     * Get the value of Post Id
     *
     * @return mixed
     */
    public function getPostId()
    {
        return $this->post_id;
    }

    /**
     * Set the value of Post Id
     *
     * @param mixed post_id
     *
     * @return self
     */
    public function setPostId($post_id)
    {
        $this->post_id = $post_id;

        return $this;
    }

}
