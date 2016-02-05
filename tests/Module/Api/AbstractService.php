<?php

namespace ModuleTest\Api;

use Zend\Json\Json;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Zend\Http\PhpEnvironment\Request;

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
        $serviceLocator->setInvokableClass('json_server','JrpcMock\Json\Server\Server');
    }

    public function tearDown()
    {
    	$this->deleteDirRec(__DIR__ . '/../../upload/');
    }
    
    public function deleteDirRec($path)
    {
    	foreach (glob($path . "/*") as $filename) {
    		(!is_dir($filename)) ? unlink($filename) : $this->deleteDirRec($filename);
    	}
    	if(is_dir($path)){
    		rmdir($path);
    	}
    }
    
    public function jsonRpc($method, array $params, $hasToken=null)
    {
        $postJson = array('method' => $method, 'id' => 1, 'params' => $params);
        
        return $this->jsonRpcRequest($postJson, $hasToken);
    }
    
    public function jsonRpcRequest($request, $hasToken=null)
    {
        if($hasToken){
            $this->getRequest()->getHeaders()->addHeaderLine('Authorization',$hasToken);
        }
        $ret = null;
        $postJson = json_encode($request);
        file_put_contents(__DIR__ . '/../../_files/input.data',$postJson);
        $this->getRequest()->setMethod('POST');
        
        $this->dispatch('/api.json-rpc');
        $response = $this->getResponse()->getContent();
    
        if(is_string($response)){
            exit($response);
        }elseif(is_array($response)) {
            foreach ($response as $r) {
                $ret[] = Json::decode($r,Json::TYPE_ARRAY);
            }
        } else {
            $ret = Json::decode($response,Json::TYPE_ARRAY);
        }
        
        return $ret;
    }

    /**
     * To test notifications' mails
     */
    public function clearMail()
    {
        return file_put_contents(__DIR__ . '/../../_files/mail.data',"");
    }

    /**
     * To test notifications' mails
     */
    public function getMail()
    {
        return file_get_contents(__DIR__ . '/../../_files/mail.data');
    }

    ///////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////
    public function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d1 = \DateTime::createFromFormat('Y-m-d\TH:i:s\Z', $date ,new \DateTimeZone('UTC'));
        $d2 = \DateTime::createFromFormat('Y-m-d H:i:s', $date,new \DateTimeZone('UTC') );

        $d = ($d1!=false) ? $d1 : (($d2!=false) ? $d2 : false);

        return $d && ($d->format('Y-m-d\TH:i:s\Z') == $date || $d->format('Y-m-d H:i:s') == $date);
    }

    public function validateToken($token)
    {
        return ( (strpos($token, 'http://cdn.local')===0) || ( strlen($token)==40 ) );
    }

    public function printCreateTest($data, $name = "\$data")
    {
        if (is_object($data) || is_array($data)) {
            print("\$this->assertEquals(count(" . $name . ") , " . count($data) . "); \n");
            foreach ($data as $key => $val) {
                $fkey = is_object($data) ? "->" . $key :  ((is_numeric($key)) ?  "[" . $key . "]" : "['" . $key . "']");
                if (is_object($val) || is_array($val)) {
                    $this->printCreateTest($val,$name . $fkey);
                } else {
                    if (is_numeric($val)) {
                        print("\$this->assertEquals(" . $name . $fkey . " , " . $val . "); \n");
                    } elseif (is_bool($val)) {
                        print("\$this->assertEquals(" . $name . $fkey . " , " . (($val) ? "true" : "false") . "); \n");
                    } elseif (is_string($val)) {
                        if(strlen($val) > 300 || $this->validateDate($val) || $this->validateToken($val)) print("\$this->assertEquals(!empty(" . $name . $fkey . ") , true); \n");
                        else print("\$this->assertEquals(" . $name . $fkey . " , \"" . $val . "\"); \n");
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
    
    public function printDocTest($datas) 
    {
    	return str_replace('"', '', json_encode($this->_printDocTest($datas)));
    }
    
    public function _printDocTest($datas)
    {
    	if (is_array($datas)) {
    		foreach ($datas as $key => $val) {
    			if (is_array($val)) {
    				$datas[$key] = $this->_printDocTest($val);
    			} else {
    				$datas[$key] = "<" . gettype($val) . ">";
    			}
    		}
    	}
    	
    	return $datas;
    }
}
