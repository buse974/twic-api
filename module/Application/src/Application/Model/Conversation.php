<?php

namespace Application\Model;

use Application\Model\Base\Conversation as BaseConversation;

class Conversation extends BaseConversation
{
    const TYPE_EMAIL = 1;
    const TYPE_CHAT = 2;
    const TYPE_VIDEOCONF = 3;
    const TYPE_ITEM_CHAT = 4;
    const TYPE_ITEM_GROUP_ASSIGNMENT = 5;
    
    const DEFAULT_NAME = 'Chat';
}
