<?php
/**
 * github.com/buse974/Dms (https://github.com/buse974/Dms).
 *
 * item
 */
namespace Application\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Plugin videoArchive
 */
class item extends AbstractPlugin
{

    /**
     * Option dms-conf
     *
     * @var \Application\Service\Item
     */
    protected $item;

    /**
     * Constructor
     *
     * @param \Application\Service\Item $item
     */
    public function __construct(\Application\Service\Item $item)
    {
        $this->item = $item;
    }

    /**
     * check Status
     *
     * @param string $json
     */
    public function itemStarting($json)
    {
        return $this->item->starting(json_decode($json, true));
    }
}
