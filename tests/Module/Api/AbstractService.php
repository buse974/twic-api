<?php

namespace ModuleTest\Api;

use Zend\Json\Json;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Zend\Http\PhpEnvironment\Request;
use Application\Model\Role as ModelRole;
use JrpcMock\Json\Server\Server;

abstract class AbstractService extends AbstractHttpControllerTestCase
{
    protected $traceError = true;

    public function setUp()
    {
        parent::setUp();
    }

    public function reset($keepPersistence = false)
    {
        parent::reset($keepPersistence);
        
        $this->setApplicationConfig(include __DIR__ . '/../../config/application.config.php');
        $serviceLocator = $this->getApplicationServiceLocator();
        $serviceLocator->setAllowOverride(true);
        $serviceLocator->setFactory('json_server_mock',
            function ($container, $requestedName, $options) {
                return new Server($container, $container->get('config')['json-rpc-server']);
            });
        $serviceLocator->setAlias('json_server', 'json_server_mock');
    }

    public function tearDown()
    {
        $this->deleteDirRec(__DIR__ . '/../../upload/');
    }

    public function deleteDirRec($path)
    {
        foreach (glob($path . "/*") as $filename) {
            (! is_dir($filename)) ? unlink($filename) : $this->deleteDirRec($filename);
        }
        if (is_dir($path)) {
            rmdir($path);
        }
    }

    public function jsonRpc($method, array $params, $hasToken = null)
    {
        $postJson = array('method' => $method,'id' => 1,'params' => $params);
        
        return $this->jsonRpcRequest($postJson, $hasToken);
    }

    public function jsonRpcRequest($request, $hasToken = null)
    {
        if ($hasToken) {
            $this->getRequest()
                ->getHeaders()
                ->addHeaderLine('Authorization', $hasToken);
        }
        $ret = null;
        $postJson = json_encode($request);
        file_put_contents(__DIR__ . '/../../_files/input.data', $postJson);
        $this->getRequest()->setMethod('POST');
        
        $this->dispatch('/api.json-rpc');
        $response = $this->getResponse()->getContent();
        
        if (is_string($response)) {
            exit($response);
        } elseif (is_array($response)) {
            foreach ($response as $r) {
                $ret[] = Json::decode($r, Json::TYPE_ARRAY);
            }
        } else {
            $ret = Json::decode($response, Json::TYPE_ARRAY);
        }
        
        return $ret;
    }

    /**
     * To test notifications' mails
     */
    public function clearMail()
    {
        return file_put_contents(__DIR__ . '/../../_files/mail.data', "");
    }

    /**
     * To test notifications' mails
     */
    public function getMail()
    {
        return file_get_contents(__DIR__ . '/../../_files/mail.data');
    }
    
    // /////////////////////////////////////////////////////////////////////////////
    // /////////////////////////////////////////////////////////////////////////////
    public function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d1 = \DateTime::createFromFormat('Y-m-d\TH:i:s\Z', $date, new \DateTimeZone('UTC'));
        $d2 = \DateTime::createFromFormat('Y-m-d H:i:s', $date, new \DateTimeZone('UTC'));
        
        $d = ($d1 != false) ? $d1 : (($d2 != false) ? $d2 : false);
        
        return $d && ($d->format('Y-m-d\TH:i:s\Z') == $date || $d->format('Y-m-d H:i:s') == $date);
    }

    public function validateToken($token)
    {
        return ((strpos($token, 'http://cdn.local') === 0) || (strlen($token) == 40));
    }

    public function printCreateTest($data, $name = "\$data")
    {
        if (is_object($data) || is_array($data)) {
            print("\$this->assertEquals(count(" . $name . ") , " . count($data) . "); \n");
            foreach ($data as $key => $val) {
                $fkey = is_object($data) ? "->" . $key : ((is_numeric($key)) ? "[" . $key . "]" : "['" . $key . "']");
                if (is_object($val) || is_array($val)) {
                    $this->printCreateTest($val, $name . $fkey);
                } else {
                    if (is_numeric($val)) {
                        print("\$this->assertEquals(" . $name . $fkey . " , " . $val . "); \n");
                    } elseif (is_bool($val)) {
                        print("\$this->assertEquals(" . $name . $fkey . " , " . (($val) ? "true" : "false") . "); \n");
                    } elseif (is_string($val)) {
                        if (strlen($val) > 300 || $this->validateDate($val) || $this->validateToken($val)) {
                            print("\$this->assertEquals(!empty(" . $name . $fkey . ") , true); \n");
                        } else {
                            print("\$this->assertEquals(" . $name . $fkey . " , \"" . $val . "\"); \n");
                        }
                    } elseif (is_null($val)) {
                        print("\$this->assertEquals(" . $name . $fkey . " , null); \n");
                    }
                }
            }
        } else {
            if (is_numeric($data)) {
                print("\$this->assertEquals(" . $name . " , " . $data . "); \n");
            } elseif (is_bool($data)) {
                print("\$this->assertEquals(" . $name . " , " . (($data) ? "true" : "false") . "); \n");
            } else {
                print("\$this->assertEquals(" . $name . " , \"" . $data . "\"); \n");
            }
        }
    }

    public function printDocTest($data, $has_data = false)
    {
        return str_replace('"', '', json_encode($this->_printDocTest($data, $has_data), JSON_PRETTY_PRINT));
    }

    public function _printDocTest($data, $has_data = false)
    {
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                if (is_array($val)) {
                    $data[$key] = $this->_printDocTest($val, $has_data);
                } else {
                    $data[$key] = ($has_data === true) ? $val : "<" . gettype($val) . ">";
                }
            }
        }
        
        return $data;
    }

    public function setIdentity($id, $role = null)
    {
        $identityMock = $this->getMockBuilder('\Auth\Authentication\Adapter\Model\Identity')
            ->disableOriginalConstructor()
            ->getMock();
        
        $rbacMock = $this->getMockBuilder('\Rbac\Service\Rbac')
            ->disableOriginalConstructor()
            ->getMock();
        
        $identityMock->expects($this->any())
            ->method('getId')
            ->will($this->returnValue($id));
        
        $identityMock->expects($this->any())
            ->method('toArray')
            ->will($this->returnValue(['id' => $id,'token' => $id . '-token','firstname' => 'toto','avatar' => 'avatar','lastname' => 'tata','organizations' => [['id' => 1],['id' => 3]]]));
        
        $authMock = $this->getMockBuilder('\Zend\Authentication\AuthenticationService')
            ->disableOriginalConstructor()
            ->getMock();
        
        $authMock->expects($this->any())
            ->method('getIdentity')
            ->will($this->returnValue($identityMock));
        
        $authMock->expects($this->any())
            ->method('hasIdentity')
            ->will($this->returnValue(true));
        
        $rbacMock->expects($this->any())
            ->method('isGranted')
            ->will($this->returnValue(true));
        
        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        
        if (null !== $role) {
            if (! is_array($role)) {
                $role = [$role];
            }
            
            $tr = [];
            foreach ($role as $rr) {
                switch ($rr) {
                    case ModelRole::ROLE_ACADEMIC_ID:
                        $tr[ModelRole::ROLE_ACADEMIC_ID] = ModelRole::ROLE_ACADEMIC_STR;
                        break;
                    case ModelRole::ROLE_ADMIN_ID:
                        $tr[ModelRole::ROLE_ADMIN_ID] = ModelRole::ROLE_ADMIN_STR;
                        break;
                    case ModelRole::ROLE_INSTRUCTOR_ID:
                        $tr[ModelRole::ROLE_INSTRUCTOR_ID] = ModelRole::ROLE_INSTRUCTOR_STR;
                        break;
                    case ModelRole::ROLE_RECRUTER_ID:
                        $tr[ModelRole::ROLE_RECRUTER_ID] = ModelRole::ROLE_RECRUTER_STR;
                        break;
                    case ModelRole::ROLE_SADMIN_ID:
                        $tr[ModelRole::ROLE_SADMIN_ID] = ModelRole::ROLE_SADMIN_STR;
                        break;
                    case ModelRole::ROLE_STUDENT_ID:
                        $tr[ModelRole::ROLE_STUDENT_ID] = ModelRole::ROLE_STUDENT_STR;
                        break;
                }
            }
            
            $userMock = $this->getMockBuilder('\Application\Service\User')->getMock();
            $userMock->expects($this->any())
                ->method('getIdentity')
                ->willReturn(['id' => $id,'token' => $id . '-token','firstname' => 'toto','avatar' => 'avatar','lastname' => 'tata','roles' => $tr,'school' => ['id' => 1,'name' => 'Morbi Corporation','short_name' => 'turpis','logo' => '','background' => ''],'organizations' => [['id' => 1],['id' => 3]],'organization_id' => 1,'wstoken' => '2437e141f8ed03a110e3292ce54c741eff6164d5','fbtoken' => 'eyJ0eXAiOiJKV1QiL']);
            
            $serviceManager->setService('app_service_user', $userMock);
        }
        
        $serviceManager->setService('auth.service', $authMock);
        $serviceManager->setService('rbac.service', $rbacMock);
    }

    public function mockRbac()
    {
        $rbacMock = $this->getMockBuilder('\Rbac\Service\Rbac')
            ->disableOriginalConstructor()
            ->getMock();
        
        $rbacMock->expects($this->any())
            ->method('isGranted')
            ->will($this->returnValue(true));
        
        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('rbac.service', $rbacMock);
    }
}
