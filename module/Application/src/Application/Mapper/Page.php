<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\Expression;

class Page extends AbstractMapper
{
    
    
    public function get($id = null, $parent_id = null, $type = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id','title','logo','background','description','confidentiality','admission','location','type', 'page$start_date' => new Expression('DATE_FORMAT(page.start_date, "%Y-%m-%dT%TZ")'),'page$end_date' => new Expression('DATE_FORMAT(page.end_date, "%Y-%m-%dT%TZ")')]);
        if(null !== $id){
            $select->where(array('page.id' => $id));
        }
        if(null !== $parent_id){
            $select>where(array('page.page_id' => $parent_id));
        }
        if(null !== $type){
            $select->where(array('page.type' => $type));
        }
        
        return $this->selectWith($select);
    }

}