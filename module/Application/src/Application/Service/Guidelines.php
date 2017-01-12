<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Guidelines
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class Guidelines.
 */
class Guidelines extends AbstractService
{
    /**
     * Add Guidelines.
     *
     * @invokable
     *
     * @param string $state
     * @param mixed  $data
     *
     * @return int
     */
    public function add($state, $data)
    {
        if ($this->getMapper()->insert(
            $this->getModel()
                ->setState($state)
                ->setData(json_encode($data))
        ) <= 0) {
            throw new \Exception('error insert guidelines');
        }

        return $this->getMapper()->getLastInsertValue();
    }

    /**
     * Update GuiideLines.
     *
     * @invokable
     *
     * @param int    $id
     * @param string $state
     * @param string $data
     *
     * @return int
     */
    public function update($id, $state = null, $data = null)
    {
        if (null !== $data) {
            $data = json_encode($data);
        }

        return $this->getMapper()->update(
            $this->getModel()
                ->setState($state)
                ->setId($id)
                ->setData($data)
        );
    }

    /**
     * Delete GuiideLines.
     *
     * @invokable
     *
     * @param int $id
     *
     * @return int
     */
    public function delete($id)
    {
        return $this->getMapper()->delete(
            $this->getModel()
                ->setId($id)
        );
    }

    /**
     * Get List GuiideLines.
     *
     * @invokable
     *
     * @param string $state
     *
     * @return array
     */
    public function getList($state)
    {
        $res_guidelines = $this->getMapper()->select(
            $this->getModel()
                ->setState($state)
        );

        $ret = [];
        foreach ($res_guidelines as $m_guidelines) {
            $data = $m_guidelines->getData();
            if (is_string($data)) {
                $ret[] = json_decode($data, true);
            }
        }

        $this->getServiceGuidelinesView()->add($state);

        return $ret;
    }

    /**
     * Get If is Viewed Or not.
     *
     * @invokable
     *
     * @param string $state
     *
     * @return bool
     */
    public function isViewed($state)
    {
        return $this->getServiceGuidelinesView()->exist($state);
    }

    /**
     * Get Service GuidelinesView.
     *
     * @return \Application\Service\GuidelinesView
     */
    private function getServiceGuidelinesView()
    {
        return $this->container->get('app_service_guidelines_view');
    }
}
