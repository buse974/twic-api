<?php

namespace Application\Service;

use Dal\Service\AbstractService;
use Zend\Db\Sql\Predicate\IsNull;
use Guzzle\Service\Resource\Model;

class BankQuestion extends AbstractService
{
    /**
     * @invokable
     * 
     * @param integer $course_id
     * @param array $data
     */
    public function add($course_id, $data) 
    {
        $ret = [];
        foreach ($data as $bq) {
            $question = (isset($bq['question'])) ? $bq['question']:null;
            $bank_question_type_id  = (isset($bq['bank_question_type_id'])) ? $bq['bank_question_type_id']:null;
            $point = (isset($bq['point'])) ? $bq['point']:null;
            $bank_question_item  = (isset($bq['bank_question_item'])) ? $bq['bank_question_item']:null;
            $bank_question_tag = (isset($bq['bank_question_tag'])) ? $bq['bank_question_tag']:null;
            $bank_question_media  = (isset($bq['bank_question_media'])) ? $bq['bank_question_media']:null;
            $name = (isset($bq['name'])) ? $bq['name']:null;
        
            $ret[] = $this->_add($course_id, $question, $bank_question_type_id, $point, $bank_question_item, $bank_question_tag, $bank_question_media, $name);
        }
        
        return $ret;
    }
    
    /**
     * @invokable
     * 
     * @param integer $id
     * @param string $question
     * @param integer $bank_question_type_id
     * @param integer $point
     * @param array $bank_question_item
     * @param array $bank_question_tag
     * @param array $bank_question_media
     * @param string $name
     */
    public function update($id, $question = null, $bank_question_type_id = null, $point = null, $bank_question_item = null, $bank_question_tag = null, $bank_question_media = null, $name = null)
    {
        $bank_question_id = $this->copy($id);
        
        $m_bank_question = $this->getModel()
            ->setId($bank_question_id)
            ->setQuestion($question)
            ->setBankQuestionTypeId($bank_question_type_id)
            ->setPoint($point)
            ->setName($name);
        
        $ret = $this->getMapper()->update($m_bank_question);
        
        if(null !== $bank_question_media) {
            $this->getServiceBankQuestionMedia()->replace($id, $bank_question_media);
        }
        
        if(null !== $bank_question_tag) {
            $this->getServiceBankQuestionTag()->replace($id, $bank_question_tag);
        }
        
        if(null !== $bank_question_item) {
            $this->getServiceBankQuestionItem()->replace($id, $bank_question_item);
        }
        
        return $ret;
    }
    
    /**
     * @param integer $id
     * @return NULL|\Application\Model\BankQuestion
     */
    public function getWithPollItemExist($id)
    {
        $res_bank_question = $this->getMapper()->getWithPollItemExist($id);
        
        return ($res_bank_question->count() > 0) ? 
            $res_bank_question->current():
            null;
    }
    
    public function copy($id)
    {
        $m_bank_question = $this->getWithPollItemExist($id);
        if(null === $m_bank_question) {
            return $id;
        }
        
        $date = (new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s');
        $this->getMapper()->update($this->getModel()->setOlder($date), ['id' => $m_bank_question->getId()]);

        $m_bank_question->setId(null)
            ->setOlder(null)
            ->setCreatedDate($date);
        
        $this->getMapper()->insert($m_bank_question);
        $bank_question_id = $this->getMapper()->getLastInsertValue();
        
        $this->getServiceBankQuestionMedia()->copy($bank_question_id, $id);
        $this->getServiceBankQuestionTag()->copy($bank_question_id, $id);
        $this->getServiceBankQuestionItem()->copy($bank_question_id, $id); 
        
        return $bank_question_id;
    }
    
    /**
     * @invokable
     *
     * @param integer $id
     */
    public function delete($id)
    {
        $this->copy($id);
        
        return $this->getMapper()->delete($this->getModel()->setId($id));
    }
    
    public function _add($course_id, $question, $bank_question_type_id, $point, $bank_question_item = null, $bank_question_tag = null, $bank_question_media = null, $name)
    {
        $m_bank_question = $this->getModel()
            ->setQuestion($question)
            ->setBankQuestionTypeId($bank_question_type_id)
            ->setPoint($point)
            ->setName($name)
            ->setCourseId($course_id)
            ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
        
        if($this->getMapper()->insert($m_bank_question) <= 0) {
            throw new \Exception('error add bank question');
        }
        
        $bank_question_id = $this->getMapper()->getLastInsertValue();
        
        if(null !== $bank_question_media) {
            $this->getServiceBankQuestionMedia()->add($bank_question_id, $bank_question_media);
        }
        
        if(null !== $bank_question_tag) {
            $this->getServiceBankQuestionTag()->add($bank_question_id, $bank_question_tag);
        }
        
        if(null !== $bank_question_item) {
            $this->getServiceBankQuestionItem()->add($bank_question_id, $bank_question_item);
        }
        
        return $bank_question_id;
    }
        
    /**
     * @invokable
     * 
     * @param integer $course_id
     */
    public function getList($course_id, $filter = null)
    {
        $mapper = (null!==$filter)?
            $this->getMapper()->usePaginator($filter):
            $this->getMapper();
        
        $res_bank_question = $mapper->select($this->getModel()->setCourseId($course_id)->setOlder(new IsNull('older')));
        
        foreach ($res_bank_question as $m_bank_question) {
            $bank_question_id = $m_bank_question->getId();
            $m_bank_question->setBankQuestionItem($this->getServiceBankQuestionItem()->getList($bank_question_id)); 
            $m_bank_question->setBankQuestionMedia($this->getServiceBankQuestionMedia()->getList($bank_question_id));
            $m_bank_question->setBankQuestionTag($this->getServiceBankQuestionTag()->getList($bank_question_id));
        }
        
        return (null!==$filter)?
            ['list' => $res_bank_question, 'count' => $mapper->count()]:
            $res_bank_question;
    }
    
    /**
     * @param integer $ids
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListLite($ids)
    {
        return $this->getMapper()->select($this->getModel()->setId($ids));
    }
    
    /**
     * 
     * @return \Application\Service\BankQuestionMedia
     */
    public function getServiceBankQuestionMedia()
    {
        return $this->getServiceLocator()->get('app_service_bank_question_media');
    }
    
    /**
     *
     * @return \Application\Service\BankQuestionTag
     */
    public function getServiceBankQuestionTag()
    {
        return $this->getServiceLocator()->get('app_service_bank_question_tag');
    }
    
    /**
     *
     * @return \Application\Service\BankQuestionItem
     */
    public function getServiceBankQuestionItem()
    {
        return $this->getServiceLocator()->get('app_service_bank_question_item');
    }
    
}