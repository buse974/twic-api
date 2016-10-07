<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * School
 */
namespace Application\Service;

use Dal\Service\AbstractService;
use JRpc\Json\Server\Exception\JrpcException;
use Application\Model\Role as ModelRole;

/**
 * Class School.
 */
class School extends AbstractService
{

    /**
     * Get custom Field
     *
     * @invokable
     *
     * @param string $libelle            
     * @return \Application\Model\School
     */
    public function getCustom($libelle)
    {
        $res_school = $this->getMapper()->getCustom($libelle);
        
        if ($res_school->count() <= 0) {
            throw new JrpcException('No custom fields for ' . $libelle);
        }
        
        return $res_school->current();
    }

    /**
     * add school
     *
     * @invokable
     *
     * @param string $name            
     * @param string $next_name            
     * @param string $short_name            
     * @param string $logo            
     * @param string $describe            
     * @param string $website            
     * @param string $background            
     * @param string $phone            
     * @param string $contact            
     * @param int $contact_id            
     * @param array $address            
     * @param string $custom            
     * @param string $libelle  
     * @param string $circle_id            
     * @throws \Exception
     * @return \Application\Model\School
     */
    public function add($name, $next_name = null, $short_name = null, $logo = null, $describe = null, $website = null, $background = null, $phone = null, $contact = null, $contact_id = null, $address = null, $custom = null, $libelle = null, $circle_id = null, $type = null)
    {
        $formattedWebsite = $this->getFormattedWebsite($website);
        $m_school = $this->getModel()
            ->setName($name)
            ->setNextName($next_name)
            ->setShortName($short_name)
            ->setLogo($logo)
            ->setDescribe($describe)
            ->setWebsite($formattedWebsite)
            ->setBackground($background)
            ->setPhone($phone)
            ->setContact($contact)
            ->setCustom($custom)
            ->setLibelle($libelle)
            ->setContactId($contact_id)
            ->setType($type);
        
        if ($address !== null) {
            $address = $this->getServiceAddress()->getAddress($address);
            if ($address && null !== ($address_id = $address->getId())) {
                $m_school->setAddressId($address_id);
            }
        }
        
        if ($this->getMapper()->insert($m_school) <= 0) {
            throw new \Exception('error insert');
        }
        
        $school_id = $this->getMapper()->getLastInsertValue();
        
        if(null !== $circle_id) {
            $this->getServiceCircle()->addOrganizations($circle_id, $school_id);
        }
        //$this->getServiceEvent()->schoolNew($school_id);
        $this->getServiceGrading()->initTpl($school_id);
        
        return $this->get($school_id);
    }

    /**
     * Generate a formatted website url for the school.
     *
     * @param string $website
     *
     * @return string
     */
    private function getFormattedWebsite($website)
    {
        $hasProtocol = strpos($website, 'http://') === 0 || strpos($website, 'https://') === 0 || strlen($website) === 0;
        return $hasProtocol ? $website : 'http://' . $website;
    }

    /**
     * Update school
     *
     * @invokable
     *
     * @param int $id            
     * @param string $name            
     * @param string $logo            
     * @param string $describe            
     * @param string $website            
     * @param string $short_name            
     * @param string $phone            
     * @param array $address            
     * @param string $background            
     * @param string $custom            
     * @param string $libelle            
     * @return int
     */
    public function update($id, $name = null, $logo = null, $describe = null, $website = null, $short_name = null, $phone = null, $address = null, $background = null, $custom = null, $libelle = null)
    {
        $formattedWebsite = $this->getFormattedWebsite($website);
        $m_school = $this->getModel()
            ->setId($id)
            ->setName($name)
            ->setLogo($logo)
            ->setDescribe($describe)
            ->setWebsite($formattedWebsite)
            ->setShortName($short_name)
            ->setPhone($phone)
            ->setBackground($background);
        
        $identity = $this->getServiceUser()->getIdentity();
        if (in_array(ModelRole::ROLE_SADMIN_STR, $identity['roles'])) {
            $m_school->setCustom($custom)->setLibelle($libelle);
        }
        
        if ($address !== null) {
            $address_id = $this->getServiceAddress()
                ->getAddress($address)
                ->getId();
            if ($address_id !== null) {
                $m_school->setAddressId($address_id);
            }
        }
        
        return $this->getMapper()->update($m_school);
    }

    /**
     * Get school
     *
     * @invokable
     *
     * @param int|array $id            
     * @return \Application\Model\School|\Dal\Db\ResultSet\ResultSet
     */
    public function get($id)
    {
        $results = $this->getMapper()->get($id);
        
        if ($m_school = $results->count() <= 0) {
            throw new \Exception('not school with id: ' . $id);
        }
        
        return (is_array($id)) ? 
            $results : 
            $results->current();
    }
    
       /**
     * Get School for mobile
     *
     * @invokable
     *
     * @param int|array $id            
     * @return array
     */
    public function m_get($id = null)
    {
        if(!is_array($id)){
            $id = [$id];
        }
        
        return $this->getMapper()->select($this->getModel()->setId($id))->toArray(['id']);
    }

    /**
     * Get school list
     *
     * @invokable
     *
     * @param array $filter            
     * @param string $search   
     * @param array $exclude         
     * @return array
     */
    public function getList($filter = null, $search = null, $exclude = null, $type = null, $parent_id = null)
    {
        $identity = $this->getServiceUser()->getIdentity();
        $is_sadmin_admin = (in_array(ModelRole::ROLE_SADMIN_STR, $identity['roles']) || in_array(ModelRole::ROLE_ADMIN_STR, $identity['roles']));
        
        $me = $identity['id'];
        $mapper = $this->getMapper();
        $res_school = $mapper->usePaginator($filter)->getList(($is_sadmin_admin) ? null:$me,$filter, $search, null, $exclude, $type, $parent_id);
        
        foreach ($res_school as $m_school) {
            $program = $this->getServiceProgram()->getListBySchool($m_school->getId());
            $m_school->setProgram(($program->count() > 0) ? $program : []);
        }
        
        return ['count' => $mapper->count(),'list' => $res_school];
    }

    /**
     * Get List organization by user
     *
     * @param int $user_id            
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function _getList($user_id)
    {
        return $this->getMapper()->getList(null, null, null, $user_id);
    }

    /**
     * Delete school.
     *
     * @invokable
     *
     * @param int $id            
     * @return array
     */
    public function delete($id)
    {
        $ret = array();
        
        if (! is_array($id)) {
            $id = array($id);
        }
        
        foreach ($id as $i) {
            $m_school = $this->getModel()
                ->setDeletedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'))
                ->setId($i);
            $ret[$i] = $this->getMapper()->update($m_school);
        }
        
        return $ret;
    }

    /**
     * Get Service Event
     *
     * @return \Application\Service\Event
     */
    private function getServiceEvent()
    {
        return $this->container->get('app_service_event');
    }

    /**
     * Get Service Address
     *
     * @return \Address\Service\Address
     */
    private function getServiceAddress()
    {
        return $this->container->get('addr_service_address');
    }

    /**
     * Get Service Program
     *
     * @return \Application\Service\Program
     */
    private function getServiceProgram()
    {
        return $this->container->get('app_service_program');
    }

    /**
     * Get Service Grading
     *
     * @return \Application\Service\Grading
     */
    private function getServiceGrading()
    {
        return $this->container->get('app_service_grading');
    }

    /**
     * Get Service Cicle
     *
     * @return \Application\Service\Circle
     */
    private function getServiceCircle()
    {
        return $this->container->get('app_service_circle');
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
}
