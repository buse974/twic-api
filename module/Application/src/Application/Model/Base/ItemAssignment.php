<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class ItemAssignment extends AbstractModel
{
    protected $id;
    protected $response;
    protected $submission_id;
    protected $submit_date;

    protected $prefix = 'item_assignment';

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setResponse($response)
    {
        $this->response = $response;

        return $this;
    }

    public function getItemProgId()
    {
        return $this->submission_id;
    }

    public function setItemProgId($submission_id)
    {
        $this->submission_id = $submission_id;

        return $this;
    }

    public function getSubmitDate()
    {
        return $this->submit_date;
    }

    public function setSubmitDate($submit_date)
    {
        $this->submit_date = $submit_date;

        return $this;
    }
}
