<?php
/**
 * 
 * TheStudnet (http://thestudnet.com)
 *
 * Item Grading
 *
 */

namespace Application\Service;

use Dal\Service\AbstractService;
use DateTime;
use DateTimeZone;

/**
 * Class ItemGrading
 */
class ItemGrading extends AbstractService
{
    /**
     * Get List Item Grading
     * 
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function _getList()
    {
        return $this->getMapper()->getList();
    }

    /**
     * Delete Item Grading
     * 
     * @param int $submission_user_id
     * @return int
     */
    public function deleteByItemProgUser($submission_user_id)
    {
        return $this->getMapper()->delete($this->getModel()->setItemProgUserId($submission_user_id));
    }

    /**
     * Add/Update Item Grading
     * 
     * @param int $submission_user_id
     * @param int $grade
     * @return int
     */
    public function add($submission_user_id, $grade)
    {
        $res_item_grading = $this->getMapper()->select($this->getModel()->setItemProgUserId($submission_user_id));
        if ($res_item_grading->count() > 0) {
            return $this->_update($res_item_grading->current()->getId(), $grade);
        } else {
            return $this->_add($submission_user_id, $grade);
        }
    }

    /**
     * Add Item Grading
     * 
     * @param int $submission_user_id
     * @param int $grade
     * @return int
     */
    public function _add($submission_user_id, $grade)
    {
        return $this->getMapper()->insert($this->getModel()
                ->setItemProgUserId($submission_user_id)
                ->setGrade($grade)
                ->setCreatedDate((new DateTime('now', new DateTimeZone('UTC')))->format('Y-m-d H:i:s'))
                );
    }

    /**
     * Update Item Grading
     * 
     * @param int $id
     * @param int $grade
     * @return int
     */
    public function _update($id, $grade)
    {
        return $this->getMapper()->update($this->getModel()
                ->setId($id)
                ->setGrade($grade)
                ->setCreatedDate((new DateTime('now', new DateTimeZone('UTC')))->format('Y-m-d H:i:s'))
        );
    }
}
