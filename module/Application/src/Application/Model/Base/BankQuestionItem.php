<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class BankQuestionItem extends AbstractModel
{
 	protected $id;
	protected $libelle;
	protected $bank_question_id;
	protected $order_id;

	protected $prefix = 'bank_question_item';

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	public function getLibelle()
	{
		return $this->libelle;
	}

	public function setLibelle($libelle)
	{
		$this->libelle = $libelle;

		return $this;
	}

	public function getBankQuestionId()
	{
		return $this->bank_question_id;
	}

	public function setBankQuestionId($bank_question_id)
	{
		$this->bank_question_id = $bank_question_id;

		return $this;
	}

	public function getOrderId()
	{
		return $this->order_id;
	}

	public function setOrderId($order_id)
	{
		$this->order_id = $order_id;

		return $this;
	}

}