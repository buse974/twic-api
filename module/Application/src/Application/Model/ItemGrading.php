<?php

namespace Application\Model;

use Application\Model\Base\ItemGrading as BaseItemGrading;

class ItemGrading extends BaseItemGrading
{
    protected $letter;

    public function setLetter($letter)
    {
        $this->letter = $letter;

        return $this;
    }

    public function getLetter()
    {
        return $this->letter;
    }
}
