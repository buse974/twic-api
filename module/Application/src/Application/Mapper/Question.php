<?php
namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\Expression;

class Question extends AbstractMapper
{

    public function selectRand($component)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id','text'))
            ->where(array('component_id' => $component))
            ->order(new Expression('RAND()'))
            ->limit(1);
        
        return $this->selectWith($select);
    }
    
    public function getList($questionnaire = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id','text'));
            
            
        if(null !== $questionnaire) {
            $select->join('questionnaire_question', 'questionnaire_question.question_id=question.id')
            ->where(array('questionnaire_question.questionnaire_id' => $questionnaire));
        }
        
        return $this->selectWith($select);
    }
}
