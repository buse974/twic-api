<?php

namespace Application\Model;

use Application\Model\Base\Item as BaseItem;

class Item extends BaseItem
{
  const TYPE_SECTION = 'SCT';
  const TYPE_FOLDER = 'FLD';

  const TYPE_LIVE_CLASS = 'LC';
  const TYPE_ASSIGNMENT = 'A';
  const TYPE_QUIZ = 'QUIZ';
  const TYPE_PAGE = 'PG';
  const TYPE_DISCUSSION = 'DISC';
  const TYPE_MEDIA = 'MEDIA';
}
