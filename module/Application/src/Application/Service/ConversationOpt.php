<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Conversation Option 
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class ConversationOpt.
 */
class ConversationOpt extends AbstractService
{
    /**
     * If item existe Add Else Update Conversation option.
     * 
     * @param int  $item_id
     * @param bool $record
     * @param int  $nb_user_autorecord
     * @param bool $allow_intructor
     * @param bool $has_eqcq
     * @param text $rules
     *
     * @return int
     */
    public function addOrUpdate($item_id, $record = null, $nb_user_autorecord = null, $allow_intructor = null, $has_eqcq = null, $rules = null)
    {
        return (null !== $this->getByItem($item_id)) ?
        $this->update($item_id, $record, $nb_user_autorecord, $allow_intructor, $has_eqcq) :
        $this->add($item_id, $record, $nb_user_autorecord, $allow_intructor, $has_eqcq);
    }

    /**
     * Create conversation opt.
     * 
     * @param int  $item_id
     * @param bool $record
     * @param int  $nb_user_autorecord
     * @param bool $allow_intructor
     * @param bool $has_eqcq
     * @param text $rules
     *
     * @return int
     */
    public function add($item_id = null, $record = 1, $nb_user_autorecord = 2, $allow_intructor = 1, $has_eqcq = 0, $rules = null)
    {
        $m_opt_videoconf = $this->getModel()
            ->setItemId($item_id)
            ->setRecord($record)
            ->setNbUserAutorecord($nb_user_autorecord)
            ->setAllowIntructor($allow_intructor)
            ->setHasEqcq($has_eqcq)
            ->setRules($rules);

        return ($this->getMapper()->insert($m_opt_videoconf) <= 0) ?
            null :
            $this->getMapper()->getLastInsertValue();
    }

    /**
     * Update Conversation option.
     * 
     * @invokable
     * 
     * @param int  $item_id
     * @param bool $record
     * @param int  $nb_user_autorecord
     * @param bool $allow_intructor
     * @param bool $has_eqcq
     * @param text $rules
     *
     * @return int
     */
    public function update($item_id, $record = null, $nb_user_autorecord = null, $allow_intructor = null, $has_eqcq = null, $rules = null)
    {
        if (null === $record && null === $nb_user_autorecord && null === $allow_intructor && null === $has_eqcq) {
            return 0;
        }

        $m_opt_videoconf = $this->getModel()
            ->setRecord($record)
            ->setNbUserAutorecord($nb_user_autorecord)
            ->setAllowIntructor($allow_intructor)
            ->setRules($rules)
            ->setHasEqcq($has_eqcq);

        return $this->getMapper()->update($m_opt_videoconf, ['item_id' => $item_id]);
    }

    /**
     * Get Option Conversation.
     * 
     * @param int $id
     *
     * @return \Application\Model\ConversationOpt
     */
    public function get($id)
    {
        $res_opt_videoconf = $this->getMapper()->select($this->getModel()->setId($id));

        return ($res_opt_videoconf->count() > 0) ? $res_opt_videoconf->current() : null;
    }

    /**
     * Get Option Conversation By Item.
     * 
     * @param int $item_id
     *
     * @return \Application\Model\ConversationOpt
     */
    public function getByItem($item_id)
    {
        $res_opt_videoconf = $this->getMapper()->select($this->getModel()->setItemId($item_id));

        return ($res_opt_videoconf->count() > 0) ? $res_opt_videoconf->current() : null;
    }
}
