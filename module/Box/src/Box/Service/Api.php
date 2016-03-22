<?php

namespace Box\Service;

use Zend\Http\Request;
use Box\Model\Document;
use Box\Model\Session;
use JRpc\Json\Server\Exception\JrpcException;

class Api extends AbstractApi
{
    /**
     * @param string $url
     * 
     * @return \Box\Model\Document
     */
    public function addFile($url)
    {
    	$this->setMethode(Request::METHOD_POST);
    	$this->setPath(sprintf('/documents'));
    	$this->setParams(['url' => $url]);
    	
    	return new Document($this->getBody($this->send()));
    }
    
    /**
     * @param integer $document_id
     * @param integer $duration
     * 
     * @return \Box\Model\Session
     */
    public function createSession($document_id, $duration = 60)
    {
    	$this->setMethode(Request::METHOD_POST);
    	$this->setPath(sprintf('/sessions'));
    	$this->setParams(['document_id' => $document_id, 'duration' => $duration]);
    	 
    	$rep = $this->send();
    	
    	if ($rep->getStatusCode() === 202) {
    	    throw new JrpcException($rep->getHeaders()->get('Retry-After'), 202);
    	} 
    	    
    	return new Session($this->getBody($rep));
    }
    
}