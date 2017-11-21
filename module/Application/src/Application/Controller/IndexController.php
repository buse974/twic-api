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
    
    const notification_type = [
        self::ITEM_STARTING => self::ITEM_STARTING
    ];
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
        $authorization = $this->conf()->getAll()['node']['authorization'];
        $request = $this->getRequest();
        syslog(1,"Notify called - Authorization : ".$request->getHeaders()->get('Authorization')->getFieldValue() );
        syslog(1,"Params : ".$this->getRequest()->getContent() );
        if($request->getHeaders()->get('Authorization') !== false && $authorization === $request->getHeaders()->get('Authorization')->getFieldValue()){
            syslog(1,"Auth ok");
            $notifs = json_decode($this->getRequest()->getContent());
            foreach($notifs as $notif){
                syslog(1,"Notif treated : ".$notif['data']['type']);
                switch($notif['data']['type']){
                    case notification_type::ITEM_STARTING : 
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
