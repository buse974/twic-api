<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class PostSubscription extends AbstractModel
{
 	protected $libelle;
	protected $post_id;
	protected $last_date;

	protected $prefix = 'post_subscription';

	public function getLibelle()
	{
		return $this->libelle;
	}

	public function setLibelle($libelle)
	{
		$this->libelle = $libelle;

		return $this;
	}

	public function getPostId()
	{
		return $this->post_id;
	}

	public function setPostId($post_id)
	{
		$this->post_id = $post_id;

		return $this;
	}

	public function getLastDate()
	{
		return $this->last_date;
	}

	public function setLastDate($last_date)
	{
		$this->last_date = $last_date;

		return $this;
	}

}