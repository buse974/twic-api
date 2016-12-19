<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class OrganizationRelation extends AbstractModel
{
    protected $organization_id;
    protected $parent_id;

    protected $prefix = 'organization_relation';

    public function getOrganizationId()
    {
        return $this->organization_id;
    }

    public function setOrganizationId($organization_id)
    {
        $this->organization_id = $organization_id;

        return $this;
    }

    public function getParentId()
    {
        return $this->parent_id;
    }

    public function setParentId($parent_id)
    {
        $this->parent_id = $parent_id;

        return $this;
    }
}
