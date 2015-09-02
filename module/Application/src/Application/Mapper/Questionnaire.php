<?php
namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Expression;

class Questionnaire extends AbstractMapper
{
    public function getByItem($item)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id','item_id','max_duration','max_time','questionnaire$created_date' => new Expression('DATE_FORMAT(questionnaire.created_date, "%Y-%m-%dT%TZ")')))
            ->where(array('questionnaire.item_id' => $item));
        
        return $this->selectWith($select);
    }
}