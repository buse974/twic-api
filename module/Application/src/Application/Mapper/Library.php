<?php
/**
 * 
 * TheStudnet (http://thestudnet.com)
 *
 * Library
 *
 */
namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

/**
 * Class Library
 */
class Library extends AbstractMapper
{

    /**
     * Get List Library By Parent Item
     *
     * @param int $item_id            
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListByParentItem($item_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id','name','link','token','type','created_date','deleted_date','updated_date','folder_id','owner_id','box_id'])
            ->join('document', 'document.library_id=library.id', [])
            ->join('item', 'document.item_id=item.id', [])
            ->where(['item.parent_id' => $item_id]);
        
        return $this->selectWith($select);
    }

    /**
     * Get List Library By Page id
     *
     * @param int $page_id
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListByPage($page_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id','name','link','token','type','created_date','deleted_date','updated_date','folder_id','owner_id','box_id'])
            ->join('page_doc', 'page_doc.library_id=library.id', [])
            ->where(['page_doc.page_id' => $page_id]);
            ->order(['created_date' => 'DESC']);
        
        return $this->selectWith($select);
    }
    
    /**
     * Get List Library By Post id
     *
     * @param int $page_id
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListByPost($post_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id','name','link','token','type','created_date','deleted_date','updated_date','folder_id','owner_id','box_id'])
        ->join('post_doc', 'post_doc.library_id=library.id', [])
        ->where(['post_doc.post_id' => $post_id]);
    
        return $this->selectWith($select);
    }
    
    /**
     * Get List Library By Item
     *
     * @param int $item_id            
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListByItem($item_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id','name','link','token','type','created_date','deleted_date','updated_date','folder_id','owner_id','box_id'])
            ->join('document', 'document.library_id=library.id', [])
            ->where(['document.item_id' => $item_id]);
        
        return $this->selectWith($select);
    }

    /**
     * Get List Library By Bank Question
     *
     * @param int $bank_question_id            
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListByBankQuestion($bank_question_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id','name','link','token','type','created_date','deleted_date','updated_date','folder_id','owner_id','box_id'])
            ->join('bank_question_media', 'bank_question_media.library_id=library.id', [])
            ->where(['bank_question_media.bank_question_id' => $bank_question_id]);
        
        return $this->selectWith($select);
    }

    /**
     * Get List Library By Conversation
     *
     * @param int $conversation_id            
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListByConversation($conversation_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id','name','link','token','type','created_date','deleted_date','updated_date','folder_id','owner_id','box_id'])
            ->join('conversation_doc', 'conversation_doc.library_id=library.id', [])
            ->where(['conversation_doc.conversation_id' => $conversation_id]);
        
        return $this->selectWith($select);
    }

    /**
     * Get List Library Material
     *
     * @param int $course_id            
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListMaterials($course_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id','name','link','token','type','created_date','deleted_date','updated_date','folder_id','owner_id','box_id'])
            ->join('material', 'material.library_id=library.id', [])
            ->where(['material.course_id' => $course_id]);
        
        return $this->selectWith($select);
    }

    /**
     * Get List Library By Submission
     *
     * @param int $submission_id            
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListBySubmission($submission_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id','name','link','token','type','created_date','deleted_date','updated_date','folder_id','owner_id','box_id'])
            ->join('document', 'document.library_id=library.id', [])
            ->where(['document.submission_id' => $submission_id]);
        
        return $this->selectWith($select);
    }

    /**
     * Get List Library By Ct
     *
     * @param int $item_id            
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListByCt($item_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id','name','link','token','type','created_date','deleted_date','updated_date','folder_id','owner_id','box_id'])
            ->join('document', 'document.library_id=library.id', [])
            ->join('ct_done', 'ct_done.target_id=item.parent_id', [])
            ->where(['ct_done.item_id' => $item_id]);
        
        return $this->selectWith($select);
    }
}
