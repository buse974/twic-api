<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Library
 */
namespace Application\Service;

use Dal\Service\AbstractService;
use Zend\Db\Sql\Predicate\IsNull;
use Box\Model\Document as ModelDocument;
use JRpc\Json\Server\Exception\JrpcException;

/**
 * Class Library.
 */
class Library extends AbstractService
{

    /**
     * Add File in library
     *
     * @invokable
     *
     * @param string $name            
     * @param string $link            
     * @param string $token            
     * @param string $type            
     * @param int $folder_id            
     * @throws \Exception
     * @return \Application\Model\Library
     */
    public function add($name, $link = null, $token = null, $type = null, $folder_id = null)
    {
        $urldms = $this->container->get('config')['app-conf']['urldms'];
        
        $box_id = null;
        $u = (null !== $link) ? $link : $urldms . $token;
        $m_box = $this->getServiceBox()->addFile($u, $type);
        
        if ($m_box instanceof ModelDocument) {
            $box_id = $m_box->getId();
        }
        
        $m_library = $this->getModel()
            ->setName($name)
            ->setLink($link)
            ->setToken($token)
            ->setBoxId($box_id)
            ->setGlobal(false)
            ->setFolderId($folder_id)
            ->setType($type)
            ->setOwnerId($this->getServiceUser()
            ->getIdentity()['id'])
            ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
        
        if ($this->getMapper()->insert($m_library) < 0) {
            throw new \Exception('Error insert file');
        }
        
        $id = $this->getMapper()->getLastInsertValue();
        
        return $this->get($id);
    }

    /**
     * Add library
     *
     * @param array $data            
     * @return \Application\Model\Library
     */
    public function _add($data)
    {
        $name = ((isset($data['name'])) ? $data['name'] : null);
        $link = ((isset($data['link'])) ? $data['link'] : null);
        $token = ((isset($data['token'])) ? $data['token'] : null);
        $type = ((isset($data['type'])) ? $data['type'] : null);
        $folder_id = ((isset($data['folder_id'])) ? $data['folder_id'] : null);
        
        return $this->add($name, $link, $token, $type, $folder_id);
    }

    /**
     * Update Library
     *
     * @invokable
     *
     * @param int $id            
     * @param string $name            
     * @param string $link            
     * @param string $token            
     * @param int $folder_id            
     * @return \Application\Model\Library
     */
    public function update($id, $name = null, $link = null, $token = null, $folder_id = null)
    {
        if ($folder_id === $id) {
            return 0;
        }
        
        $m_library = $this->getModel()
            ->setId($id)
            ->setName($name)
            ->setLink($link)
            ->setToken($token)
            ->setFolderId(($folder_id === 0) ? new IsNull() : $folder_id)
            ->setUpdatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
        
        $this->getMapper()->update($m_library);
        
        return $this->get($id);
    }

    /**
     * Get List Library
     *
     * @invokable
     *
     * @param int $folder_id            
     * @return array
     */
    public function getList($filter = null, $folder_id = null, $global = null)
    {
        if(null === $global) {
            $global = false;
        }
        
        $user_id = $this->getServiceUser()->getIdentity()['id'];
        $m_library = $this->getModel()
            ->setFolderId(null !== $folder_id ? $folder_id : new IsNull())
            ->setDeletedDate(new IsNull())
            ->setGlobal($global)
            ->setOwnerId($user_id);
       
        $mapper = (null !== $filter) ?
            $this->getMapper()->usePaginator($filter) : 
            $this->getMapper();
        
        $res_library = $mapper->select($m_library);
        $ar = [
            'count' => $mapper->count(), 
            'documents' => $res_library, 
            'folder' => null,
            'parent' => null
        ];
        // If root folder: returns only documents
        if ($folder_id) {
            // Requested document / folder
            $folder = $this->getMapper()->select($this->getModel()->setId($folder_id))->current();
            // Parent folder
            $parent = ($folder && is_numeric($folder->getFolderId())) ?
                $this->getMapper()->select($this->getModel()->setId($folder->getFolderId()))->current() : null;
          
            $ar['folder'] = $folder;
            $ar['parent'] = $parent;
        }
        
        return $ar;
    }

    /**
     * Get List Library By item id
     *
     * @invokable
     *
     * @param int $item            
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListByItem($item)
    {
        return $this->getMapper()->getListByItem($item);
    }

    /**
     * Get List Library By item parent id
     *
     * @invokable
     *
     * @param int $item            
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListByParentItem($item)
    {
        return $this->getMapper()->getListByParentItem($item);
    }

    /**
     * Get List Library By bank question id
     *
     * @param int $bank_question_id            
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListByBankQuestion($bank_question_id)
    {
        return $this->getMapper()->getListByBankQuestion($bank_question_id);
    }
    
    /**
     * Get List Library By Page id
     *
     * @param int $page_id
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListByPage($page_id)
    {
        return $this->getMapper()->getListByPage($page_id);
    }

    /**
     * Get List Library By Post id
     *
     * @param int $post_id
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListByPost($post_id)
    {
        return $this->getMapper()->getListByPost($post_id);
    }
    
    /**
     * Get List Library Material
     *
     * @param int $course_id            
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListMaterials($course_id)
    {
        return $this->getMapper()->getListMaterials($course_id);
    }

    /**
     * Get List Library By Submission id
     *
     * @invokable
     *
     * @param int $submission_id            
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListBySubmission($submission_id)
    {
        return $this->getMapper()->getListBySubmission($submission_id);
    }

    /**
     * Get List Library By conversation id
     *
     * @param int $conversation_id            
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListByConversation($conversation_id)
    {
        return $this->getMapper()->getListByConversation($conversation_id);
    }

    /**
     * Get Library By item id
     *
     * @param int $item_id            
     * @return \Application\Model\Library
     */
    public function getByItem($item_id)
    {
        $res_library = $this->getMapper()->getListByItem($item);
        
        return ($res_library->count() > 0) ? $res_library->current() : null;
    }

    /**
     * Get List By Constrainte
     *
     * @invokable
     *
     * @param int $item            
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListByCt($item)
    {
        return $this->getMapper()->getListByCt($item);
    }

    /**
     * delete Library
     *
     * @invokable
     *
     * @param int $id            
     * @return int
     */
    public function delete($id)
    {
        $m_library = $this->getModel()
            ->setId($id)
            ->setDeletedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
        
        return $this->getMapper()->update($m_library);
    }

    /**
     * Get Library
     *
     * @invokable
     *
     * @param int|array $id            
     * @return \Application\Model\Library|\Dal\Db\ResultSet\ResultSet
     */
    public function get($id)
    {
        $res_library = $this->getMapper()->select($this->getModel()
            ->setId($id));
        
        return (is_array($id)) ? $res_library : $res_library->current();
    }

    /**
     * Get Box Session
     *
     * @invokable
     *
     * @param int $id            
     * @param string $box_id            
     * @throws \Exception
     * @throws JrpcException
     * @return void|\Box\Model\Session
     */
    public function getSession($id = null, $box_id = null)
    {
        if (null === $id && null === $box_id) {
            return;
        }
        
        if (null !== $id) {
            $res_library = $this->getMapper()->select($this->getModel()
                ->setId($id));
            
            if ($res_library->count() <= 0) {
                throw new \Exception();
            }
            $m_library = $res_library->current();
            $box_id = $m_library->getBoxId();
            if (empty($box_id)) {
                throw new JrpcException('No Box Id', 123456);
            }
        }
        
        $session = null;
        try {
            $session = $this->getServiceBox()->createSession($box_id);
        } catch (\Exception $e) {
            throw new JrpcException($e->getMessage(), $e->getCode());
        }
        
        return $session;
    }

    /**
     * Get Service User
     *
     * @return \Application\Service\User
     */
    private function getServiceUser()
    {
        return $this->container->get('app_service_user');
    }

    /**
     * Get Service Box Api
     *
     * @return \Box\Service\Api
     */
    private function getServiceBox()
    {
        return $this->container->get('box.service');
    }
}
