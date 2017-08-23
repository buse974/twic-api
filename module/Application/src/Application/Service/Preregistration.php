<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class Preregistration extends AbstractService
{
      /**
     * Get Pregestration
     *
     * @invokable
     *
     * @param  string $account_token
     * @return array
     */
    public function get($account_token)
    {
        return $this->getMapper()->select(
            $this->getModel()
                ->setAccountModel($account_token)
        )->current();
    }
    
}