<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class ItemProgUserRelation extends AbstractService
{
 	public function add($user, $item_prog)
    {
        $ret = array();

        foreach ($user as $u) {
            foreach ($item_prog as $i) {
                $ret[$u][$i] = $this->getMapper()->insertUserItemProg($i, $u);
            }
        }

        return $ret;
    }
}
