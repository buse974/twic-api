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
        $notifs = $this->getRequest()->getContent();
        foreach($notifs as $notif){
            switch($notif['type']['type']){
                case notification_type::ITEM_STARTING : 
                    $ret = $this->item()->starting($notif['data']['id']);
                break;
            }
        }

        return new JsonModel(['code'=>$ret]);
    }
}
