<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class ItemProgUserRelation extends AbstractMapper
{
	public function insertUserProgram($i, $u)
	{
		$req = 'INSERT INTO `item_prog_user_relation` (`item_user_id`,`user_id`) SELECT * FROM (select '.$i.' as p,'.$u.' as u) as t WHERE NOT EXISTS
(SELECT * FROM item_prog_user_relation WHERE item_prog_user_relation='.$i.' AND user_id='.$u.') LIMIT 1;';
	
		return $this->requestPdo($req);
	}
}
