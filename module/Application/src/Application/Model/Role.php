<?php

namespace Application\Model;

use Application\Model\Base\Role as BaseRole;

class Role extends BaseRole
{
    const ROLE_SADMIN_ID     = 1;
    const ROLE_ADMIN_ID      = 2;
    const ROLE_ACADEMIC_ID   = 3;
    const ROLE_STUDENT_ID    = 4;
    const ROLE_INSTRUCTOR_ID = 5;
    const ROLE_RECRUTER_ID   = 6;
    
    const ROLE_SADMIN_STR     = 'sadmin';
    const ROLE_ADMIN_STR      = 'admin';
    const ROLE_ACADEMIC_STR   = 'academic';
    const ROLE_STUDENT_STR    = 'student';
    const ROLE_INSTRUCTOR_STR = 'instructor';
    const ROLE_RECRUTER_STR   = 'recruiter';
    
    static public $role = array(
        self::ROLE_SADMIN_ID => self::ROLE_SADMIN_STR,
        self::ROLE_ADMIN_ID => self::ROLE_ADMIN_STR,
        self::ROLE_ACADEMIC_ID => self::ROLE_ACADEMIC_STR,
        self::ROLE_STUDENT_ID => self::ROLE_STUDENT_STR,
        self::ROLE_INSTRUCTOR_ID => self::ROLE_INSTRUCTOR_STR,
        self::ROLE_RECRUTER_ID => self::ROLE_RECRUTER_STR
    );

}
