<?php

namespace Auth\Authentication\Adapter;

use Zend\Authentication\Adapter\AbstractAdapter;
use Zend\Db\Adapter\Adapter;
use Zend\Authentication\Result;
use Zend\Db\Sql\Sql as DbSql;
use Auth\Authentication\Adapter\Model\Identity;
use Zend\Math\Rand;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Predicate\Predicate;
use Zend\Db\Sql\Predicate\IsNotNull;

class DbAdapter extends AbstractAdapter
{
    const FAILURE_ACCOUNT_SUSPENDED = -5;
    /**
     * Bdd Adapter.
     *
     * @var Adapter
     */
    protected $db_adapter;

    /**
     * @var string
     */
    protected $table;

    /**
     * @var \Auth\Auth\Authentication\Adapter\Model\IdentityInterface
     */
    protected $result;

    /**
     * @var string
     */
    protected $hash;

    /**
     * @var string
     */
    protected $identity_column;

    /**
     * @var string
     */
    protected $credential_column;

    /**
     * Sets username and password for authentication.
     */
    public function __construct(Adapter $db_adapter, $table, $identity_column, $credential_column, $hash = 'MD5(?)', $result = null)
    {
        $this->db_adapter = $db_adapter;
        $this->table = $table;
        $this->identity_column = $identity_column;
        $this->credential_column = $credential_column;
        $this->result = $result;
        $this->hash = $hash;
    }

    /**
     * Performs an authentication attempt.
     *
     * @return \Zend\Authentication\Result
     *
     * @throws \Zend\Authentication\Adapter\Exception\ExceptionInterface
     */
    public function authenticate()
    {
        $code = Result::FAILURE;
        $message = array();
        $identity = null;

        $sql = new DbSql($this->db_adapter);
        $select = $sql->select();
        $select->from($this->table)
            ->columns(array('*'))
            ->where(array(' ( user.password = MD5(?) ' => $this->credential))
            ->where(array('user.new_password = MD5(?) )' => $this->credential), Predicate::OP_OR)
            ->where(array('user.'.$this->identity_column.' = ? ' => $this->identity))
            ->where(array('user.deleted_date IS NULL'));

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        if ($results->count() < 1) {
            $code = Result::FAILURE_CREDENTIAL_INVALID;
            $message[] = 'A record with the supplied identity could not be found.';
        } elseif ($results->count() > 1) {
            $code = Result::FAILURE_IDENTITY_AMBIGUOUS;
            $message[] = 'More than one record matches the supplied identity.';
        } else {

            $arrayIdentity = (new ResultSet())->initialize($results)->toArray();
            $arrayIdentity = current($arrayIdentity);
            if(null !== $arrayIdentity['suspension_date']){
                $code = self::FAILURE_ACCOUNT_SUSPENDED;
                $message[] = $arrayIdentity['suspension_reason'];
            }
            else{
                
                $code = Result::SUCCESS;
                $message[] = 'Authentication successful.';
                $identity = $this->getResult()->exchangeArray($arrayIdentity);
                $identity->setToken($identity->getId().md5($identity->getId().$identity->getEmail().Rand::getBytes(10).time()));
                $update = $sql->update('user');
                $update->set(array('password' => md5($this->credential), 'new_password' => null))->where(array('id' => $arrayIdentity['id'], new IsNotNull('new_password')));
                $statement = $sql->prepareStatementForSqlObject($update);
                $statement->execute();
            }
            
        }
        return new Result($code, $identity, $message);
    }

    /**
     * @return \Auth\Auth\Authentication\Adapter\Model\IdentityInterface
     */
    public function getResult()
    {
        if (null === $this->result) {
            $this->result = new Identity();
        }

        return $this->result;
    }
}
