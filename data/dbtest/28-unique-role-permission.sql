DELETE rp FROM role_permission rp, role_permission rp2 
WHERE rp.id > rp2.id AND rp.role_id = rp2.role_id AND rp.permission_id = rp2.permission_id;

ALTER TABLE `role_permission` 
ADD UNIQUE INDEX `index4` (`role_id` ASC, `permission_id` ASC);

