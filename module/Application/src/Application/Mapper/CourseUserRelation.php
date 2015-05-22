<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class CourseUserRelation extends AbstractMapper
{
	public function insertUserCourse($c, $u)
	{
		$req = 'INSERT INTO `course_user_relation` (`course_id`,`user_id`) SELECT * FROM (select '.$c.' as c,'.$u.' as u) as t WHERE NOT EXISTS
				(SELECT * FROM course_user_relation WHERE course_id='.$c.' AND user_id='.$u.') LIMIT 1;';
	
		return $this->requestPdo($req);
	}
}
