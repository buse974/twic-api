<?php

namespace Application\Service;

use Dal\Service\AbstractService;
use DateTime;
use DateTimeZone;
use Zend\Db\Sql\Predicate\IsNull;

class MaterialDocument extends AbstractService
{
    /**
     * Add Material Document.
     *
     * @invokable
     *
     * @param int    $course_id
     * @param string $type
     * @param string $title
     * @param string $autor
     * @param string $link
     * @param string $source
     * @param string $token
     * @param string $date
     *
     * @throws \Exception
     *
     * @return int
     */
    public function add($course_id, $type = null, $title = null, $author = null, $link = null, $source = null, $token = null, $date = null)
    {
        $m_material_document = $this->getModel()
                                    ->setCourseId($course_id)
                                    ->setType($type)
                                    ->setTitle($title)
                                    ->setAuthor($author)
                                    ->setLink($link)
                                    ->setSource($source)
                                    ->setToken($token)
                                    ->setDate($date)
                                    ->setCreatedDate((new DateTime('now', new DateTimeZone('UTC')))->format('Y-m-d H:i:s'));

        if ($this->getMapper()->insert($m_material_document) <= 0) {
            throw new \Exception('error insert Material document');
        }

        $material_document = $this->getMapper()->getLastInsertValue();
        $this->getServiceEvent()->courseMaterialAdded($course_id, $material_document);

        return $material_document;
    }

    /**
     * Delete Material Document by Course Id.
     *
     * @param int $course_id
     *
     * @return int
     */
    public function deleteByCourseId($course_id)
    {
        $m_material_document = $this->getModel()->setDeletedDate((new DateTime('now', new DateTimeZone('UTC')))->format('Y-m-d H:i:s'));

        return $this->getMapper()->update($m_material_document, array('course_id' => $course_id));
    }

    /**
     * Update Material Document by Course Id.
     *
     * @invokable
     *
     * @param int    $id
     * @param string $type
     * @param string $title
     * @param string $author
     * @param string $link
     * @param string $source
     * @param string $token
     * @param string $date
     *
     * @return int
     */
    public function update($id, $type = null, $title = null, $author = null, $link = null, $source = null, $token = null, $date = null)
    {
        $m_material_document = $this->getModel()
                                    ->setId($id)
                                    ->setUpdatedDate((new DateTime('now', new DateTimeZone('UTC')))->format('Y-m-d H:i:s'))
                                    ->setType($type)
                                    ->setTitle($title)
                                    ->setAuthor($author)
                                    ->setLink($link)
                                    ->setSource($source)
                                    ->setToken($token)
                                    ->setDate($date);

        return $this->getMapper()->update($m_material_document);
    }

    /**
     * Update Material Document.
     *
     * @invokable
     *
     * @param delete $id
     *
     * @return int
     */
    public function delete($id)
    {
        $m_material_document = $this->getModel()
                                    ->setId($id)
                                    ->setDeletedDate((new DateTime('now', new DateTimeZone('UTC')))->format('Y-m-d H:i:s'));

        return $this->getMapper()->update($m_material_document);
    }

    /**
     * Get List material document by course id.
     *
     * @param int $course_id
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListByCourse($course_id)
    {
        return $this->getMapper()->select($this->getModel()->setCourseId($course_id)->setDeletedDate(new IsNull()));
    }

    /**
     * Get material document.
     *
     * @param integrer $id
     *
     * @return \Application\Model\MaterialDocument
     */
    public function get($id)
    {
        return $this->getMapper()->select($this->getModel()->setId($id))->current();
    }

    /**
     * Get List material document by item id.
     *
     * @param int $item
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListByItem($item)
    {
        return $this->getMapper()->getListByItem($item);
    }

    /**
     * @return \Application\Service\Event
     */
    public function getServiceEvent()
    {
        return $this->getServiceLocator()->get('app_service_event');
    }
}
