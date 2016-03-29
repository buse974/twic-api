<?php

namespace Application\Service;

use Dal\Service\AbstractService;
use Zend\Db\Sql\Predicate\IsNull;

class Library extends AbstractService
{
	/**
	 * @invokable
	 * 
	 * @param string $name
	 * @param string $link
	 * @param string $token
	 * @param string $type
	 * @param integer $folder_id
	 * @throws \Exception
	 * 
	 * @return integer
	 */
	public function add($name, $link = null, $token = null, $type = null, $folder_id = null)
	{
	    $urldms = $this->getServiceLocator()->get('config')['app-conf']['urldms'];
	    $res_box = $this->getServiceBox()->addFile(($link)?:$urldms.$token);
		$m_library = $this->getModel()
			->setName($name)
			->setLink($link)
			->setToken($token)
			->setBoxId($res_box->getId())
			->setFolderId($folder_id)
			->setType($type)
			->setOwnerId($this->getServiceUser()->getIdentity()['id'])
			->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));

		if($this->getMapper()->insert($m_library) < 0) {
			throw new \Exception('Error insert file');
		}
		
		$id = $this->getMapper()->getLastInsertValue();
		
		return $this->get($id);
	}
	
	/**
	 * @invokable
	 *
	 * @param integer $id
	 * @param string $name
	 * @param string $link
	 * @param string $token
	 * @param integer $folder_id
	 */
	public function update($id, $name = null, $link = null, $token = null, $folder_id = null)
	{
		$m_library = $this->getModel()
			->setId($id)
			->setName($name)
			->setLink($link)
			->setToken($token)
			->setFolderId(($folder_id === 0)? new IsNull():$folder_id)
			->setUpdatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
	
	 	$this->getMapper()->update($m_library);
	 	
	 	return $this->get($id);
	}
	
	/**
	 * @invokable
	 * 
	 * @param integer $folder_id
	 */
	public function getList($folder_id = null)
	{
		$m_library = $this->getModel()
		    ->setFolderId(($folder_id == null)? new IsNull():$folder_id)
		    ->setDeletedDate(new IsNull())
		    ->setOwnerId($this->getServiceUser()->getIdentity()['id']);
		
		return $this->getMapper()->select($m_library);
	}
	
	/**
	 * @invokable
	 *
	 * @param integer $id
	 */
	public function delete($id)
	{
		$m_library = $this->getModel()
			->setId($id)
			->setDeletedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
	 	return $this->getMapper()->update($m_library);
	}
	
	/**
	 * @invokable
	 * 
	 * @param integer $id
	 */
	public function get($id)
	{
		return $this->getMapper()->select($this->getModel()->setId($id)->setOwnerId($this->getServiceUser()->getIdentity()['id']))->current();
	}
	
	/**
	 * @invokable
	 *
	 * @param integer $id
	 */
	public function getSession($id)
	{
	    $m_library = $this->getMapper()->select($this->getModel()->setId($id)->setOwnerId($this->getServiceUser()->getIdentity()['id']))->current();
	    
	    return $this->getServiceBox()->createSession($m_library->getBoxId());
	}
	
	/**
	 * @return \Application\Service\User
	 */
	public function getServiceUser()
	{
		return $this->getServiceLocator()->get('app_service_user');
	}
	
	/**
	 * @return \Box\Service\Api
	 */
	public function getServiceBox()
	{
	    return $this->getServiceLocator()->get('box.service');
	}
	
}