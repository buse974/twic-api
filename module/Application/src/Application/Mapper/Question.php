<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\Expression;

class Question extends AbstractMapper
{
    public function selectRand($component)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'text'))
            ->where(array('component_id' => $component))
            ->where(array('question.deleted_date IS NULL'))
            ->order(new Expression('RAND()'))
            ->limit(1);

        return $this->selectWith($select);
    }

    public function getList($questionnaire = null, $dimension = null, $search = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'text'))
            ->join('component', 'component.id=question.component_id', array('id', 'name'));

        if (null !== $questionnaire) {
            $select->join('questionnaire_question', 'questionnaire_question.question_id=question.id', array())
                ->where(array('questionnaire_question.questionnaire_id' => $questionnaire));
        }

        if (null !== $dimension) {
            $select->join('dimension', 'dimension.id=component.dimension_id', array())
                ->where(array('dimension.name' => $dimension));
        }

        if (null !== $search) {
            $select->where(array('question.text LIKE ?' => '%'.$search.'%'));
        }

        $select->where(array('question.deleted_date IS NULL'));

        return $this->selectWith($select);
    }
}
