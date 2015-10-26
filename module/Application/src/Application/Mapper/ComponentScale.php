<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class ComponentScale extends AbstractMapper
{
    public function getList($component_id)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('id', 'component_id', 'min', 'max', 'describe', 'recommandation'))
            ->join('component', 'component.id=component_scale.component_id', array('id', 'name'));

        if (null !== $component_id) {
            $select->where(array('component.id' => $component_id));
        }

        return $this->selectWith($select);
    }
}
