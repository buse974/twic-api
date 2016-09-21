<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\Expression;

class Page extends AbstractMapper
{

    public function getList($id = null, $parent_id = null, $user_id = null, $organization_id = null, $type = null, $start_date = null, $end_date = null)
    {
        $where = $this->getWhereParams([
            'id' => $id,
            'parent_id' => $parent_id,
            'user_id' => $user_id,
            'organization_id' => $organization_id,
            'type' => $type
        ]);

        $select = $this->tableGateway->getSql()->select()
            ->columns(['id','title','logo','background','description','confidentiality','admission','location','type', 'page$start_date' => new Expression('DATE_FORMAT(page.start_date, "%Y-%m-%dT%TZ")'),'page$end_date' => new Expression('DATE_FORMAT(page.end_date, "%Y-%m-%dT%TZ")')])    
            ->where($where);

        if (null !== $end_date) {
            $select->where([
                'page.start_date <= ?' => [$end_date]
            ]);
        }

        if (null !== $start_date) {
            $select->where([
                'page.end_date >= ?' => [$start_date]
            ]);
        }

        return $this->selectWith($select);
    }

    protected function getWhereParams($originalParams = [])
    {
        return array_filter($originalParams, function($value) {
            return null !== $value;
        });
    }


    
    
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
