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
        $this->setApplicationConfig(include __DIR__ . '/../../config/application.config.php');
        parent::setUp();
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
    
    public function jsonRpc($method, array $params, $hasToken=true)
    {
    	if($hasToken){
    		$this->getRequest()->getHeaders()->addHeaderLine('Authorization',(is_bool($hasToken)) ?array('token' => rand(100, 1000)):$hasToken);
    	}
    	
        $postJson = json_encode(array('method' => $method, 'id' => 1, 'params' => $params));
        file_put_contents(__DIR__ . '/../../_files/input.data',$postJson);
        $this->getRequest()->setMethod('POST');
        $this->dispatch('/api.json-rpc');
        $response = $this->getResponse()->getContent();
        
        if(is_string($response)){
        	exit($response);	
        }
        
        return Json::decode($response,Json::TYPE_ARRAY);
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
        $d = \DateTime::createFromFormat($format, $date);

        return $d && $d->format($format) == $date;
    }

    public function validateToken($token)
    {
        return ( (strpos($token, 'http://cdn.local')===0) || ( strlen($token)==40 ) );
    }

    public function printCreateTest($datas, $name = "\$datas")
    {
        if (is_object($datas) || is_array($datas)) {
            print("\$this->assertEquals(count(" . $name . ") , " . count($datas) . "); \n");
            foreach ($datas as $key => $val) {
                $fkey = is_object($datas) ? "->" . $key :  ((is_numeric($key)) ?  "[" . $key . "]" : "['" . $key . "']");
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
            if (is_numeric($datas)) {
                print("\$this->assertEquals(" . $name . " , " . $datas . "); \n");
            } elseif (is_bool($datas)) {
                print("\$this->assertEquals(" . $name . " , " . (($datas) ? "true" : "false") . "); \n");
            } else {
                print("\$this->assertEquals(" . $name . " , \"" . $datas . "\"); \n");
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
