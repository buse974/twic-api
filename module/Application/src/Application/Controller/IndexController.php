<?php

/**
 * Zend Framework (http://framework.zend.com/).
 *
 * @link http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 *
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use JRpc\Json\Server\Exception\JrpcException;

/**
 * Controller Index
 */
class IndexController extends AbstractActionController
{
    const ITEM_STARTING = 'item.starting';
    /**
     * Index
     *
     * {@inheritDoc}
     *
     * @see \Zend\Mvc\Controller\AbstractActionController::indexAction()
     */
    public function indexAction()
    {
        $response = $this->getResponse();
        $response->getHeaders()->addHeaderLine('Location', 'doc/index.html');
        $response->setStatusCode(302);
        
        return $response;
    }
    
    /**
     * Check Status
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function statusChangeAction()
    {
        $ret = $this->videoArchive()->checkStatus($this->getRequest()->getContent());

        return new JsonModel(['code'=>$ret]);
    }
    
      /**
     * Check Status
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function notifyAction()
    {
        syslog(1, "TU VIENS D'APPELER LA ROUTE NOTIFY ET TU AURAIS PU AJOUTER CE SYSLOG TOUT SEUL ESPECE DE *******!");
        syslog(1, "\nPARAMS : ".$this->getRequest()->getContent());
        $authorization = $this->conf()->getAll()['node']['authorization'];
        syslog(1, "CONF API : ".$authorization);
        $request = $this->getRequest();
        syslog(1, "SI CE SYSLOG APPARAIT C'EST QUE LE GET REQUEST FONCTIONNE");
        syslog(1, "HEADERS : ".json_encode($request->getHeaders()));
        syslog(1, "HEADER AUTH : ".json_encode($request->getHeaders()->get('Authorization')) );
        syslog(1, "HEADER AUTH VALUE : ".json_encode($request->getHeaders()->get('Authorization')->getFieldValue()) );
        syslog(1, "AUTHORIZATION : ".$authorization . ' === '. $request->getHeaders()->get('Authorization')->getFieldValue() . ' ?');
        if($request->getHeaders()->get('Authorization') !== false && $authorization === $request->getHeaders()->get('Authorization')->getFieldValue()){
            $notifs = json_decode($this->getRequest()->getContent(), true);
            foreach($notifs as $notif){
                syslog(1, self::ITEM_STARTING. ' == ' .$notif['type']);
                switch($notif['type']){
                    case self::ITEM_STARTING : 
                        $ret = $this->item()->starting($notif['data']['id']);
                    break;
                }
            }

            return new JsonModel(['code'=>$ret]);
        }
        else{
            throw new JrpcException('No authorization: notify', - 32029);
        }
       
    }
}
