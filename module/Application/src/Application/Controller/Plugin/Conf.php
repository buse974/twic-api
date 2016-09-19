<?php
/**
 * github.com/buse974/Dms (https://github.com/buse974/Dms).
 *
 * Dms
 */
namespace Application\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Plugin Dms.
 */
class Conf extends AbstractPlugin
{

    /**
     * Option dms-conf
     *
     * @var array
     */
    protected $options;

    /**
     * Constructor
     *
     * @param DmsService $service
     * @param array $options
     */
    public function __construct($options)
    {
        $this->options = $options;
    }

    /**
     * Get Array Hearders
     *
     * @return array
     */
    public function getVersion()
    {
        return $this->options['version'];
    }
}
