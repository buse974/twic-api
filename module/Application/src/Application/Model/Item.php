<?php

namespace Application\Model;

use Application\Model\Base\Item as BaseItem;

class Item extends BaseItem
{
    const TYPE_LIVE_CLASS = 'LC';
    const TYPE_WORKGROUP = 'WG';
    const TYPE_INDIVIDUAL_ASSIGMENT = 'IA';
    const TYPE_CAPSTONE_PROJECT = 'CP';
}
