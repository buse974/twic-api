<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class Document extends AbstractModel
{
    protected $id;
    protected $item_id;
    protected $submission_id;
    protected $library_id;
    protected $created_date;
    protected $deleted_date;
    protected $updated_date;

    protected $prefix = 'document';

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getItemId()
    {
        return $this->item_id;
    }

    public function setItemId($item_id)
    {
        $this->item_id = $item_id;

        return $this;
    }

    public function getSubmissionId()
    {
        return $this->submission_id;
    }

    public function setSubmissionId($submission_id)
    {
        $this->submission_id = $submission_id;

        return $this;
    }

    public function getLibraryId()
    {
        return $this->library_id;
    }

    public function setLibraryId($library_id)
    {
        $this->library_id = $library_id;

        return $this;
    }

    public function getCreatedDate()
    {
        return $this->created_date;
    }

    public function setCreatedDate($created_date)
    {
        $this->created_date = $created_date;

        return $this;
    }

    public function getDeletedDate()
    {
        return $this->deleted_date;
    }

    public function setDeletedDate($deleted_date)
    {
        $this->deleted_date = $deleted_date;

        return $this;
    }

    public function getUpdatedDate()
    {
        return $this->updated_date;
    }

    public function setUpdatedDate($updated_date)
    {
        $this->updated_date = $updated_date;

        return $this;
    }
}
