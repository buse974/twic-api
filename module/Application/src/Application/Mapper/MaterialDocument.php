<?php
namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\Predicate;

class MaterialDocument extends AbstractMapper
{

    /**
     * Get List material document by item id.
     *
     * @param int $item            
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListByItem($item)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->columns(array('id','course_id','type','title','author','link','source','token', 'description'))
            ->join('item_material_document_relation', 'item_material_document_relation.material_document_id = material_document.id')
            ->where(array('item_material_document_relation.item_id ' => $item));
        
        return $this->selectWith($select);
    }

    /**
     *
     * @param integer $school            
     */
    public function nbrTotal($school, $day = null)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->columns(array('material_document$document' => 'id'))
            ->join('item_material_document_relation', 'item_material_document_relation.material_document_id = material_document.id', array())
            ->join('item', 'item.id = item_material_document_relation.item_id', array())
            ->join('course', 'item.course_id = course.id', array())
            ->join('program', 'course.program_id = program.id', array())
            ->join('item_prog', 'item_prog.id = item_material_document_relation.item_id', array())
            ->join('item_prog_user', 'item_prog_user.item_prog_id = item_prog.id', array('material_document$user' => 'user_id'))
            ->where(array('program.school_id' => $school))
            ->where(array(' ( material_document.link IS NOT NULL OR material_document.token IS NOT NULL ) '))
            ->quantifier('DISTINCT');
        
        if (null !== $day) {
            $select->where(array('((item_prog.due_date > DATE_ADD(UTC_TIMESTAMP(), INTERVAL -' . $day . ' DAY) AND (item.type = \'CP\' OR item.type = \'IA\'))'))->where(array('(item_prog.start_date > DATE_ADD(UTC_TIMESTAMP(), INTERVAL -' . $day . ' DAY) AND (item.type = \'LC\' OR item.type = \'WG\')))'), Predicate::OP_OR);
        }
        
        return $this->selectWith($select);
    }

    /**
     *
     * @param integer $school            
     */
    public function nbrView($school, $day = null)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->columns(array('material_document$document' => 'id'))
            ->join('item_material_document_relation', 'item_material_document_relation.material_document_id = material_document.id', array())
            ->join('item', 'item.id = item_material_document_relation.item_id', array())
            ->join('course', 'item.course_id = course.id', array())
            ->join('program', 'course.program_id = program.id', array())
            ->join('item_prog', 'item_prog.id = item_material_document_relation.item_id', array())
            ->join('item_prog_user', 'item_prog_user.item_prog_id = item_prog.id', array('material_document$user' => 'user_id'))
            ->join('activity', 'activity.object_id=material_document.id AND activity.user_id=item_prog_user.user_id', array())
            ->where(array('activity.event' => 'course.material.view'))
            ->where(array('activity.object_name' => 'course.material'))
            ->where(array('program.school_id' => $school))
            ->where(array(' ( material_document.link IS NOT NULL OR material_document.token IS NOT NULL ) '))
            ->quantifier('DISTINCT');
        
        if (null !== $day) {
            $select->where(array('(( item_prog.due_date > DATE_ADD(UTC_TIMESTAMP(), INTERVAL -' . $day . ' DAY) AND item_prog.due_date < UTC_TIMESTAMP() AND (item.type = \'CP\' OR item.type = \'IA\'))'))
                ->where(array('( item_prog.start_date > DATE_ADD(UTC_TIMESTAMP(), INTERVAL -' . $day . ' DAY) AND item_prog.start_date < UTC_TIMESTAMP() AND (item.type = \'LC\' OR item.type = \'WG\')))'), Predicate::OP_OR);
        } else {
            $select->where(array('(( item_prog.due_date < UTC_TIMESTAMP() AND (item.type = \'CP\' OR item.type = \'IA\'))'))
                ->where(array('( item_prog.start_date < UTC_TIMESTAMP() AND (item.type = \'LC\' OR item.type = \'WG\')))'), Predicate::OP_OR);
        }
        
        return $this->selectWith($select);
    }
}
