<?php


namespace App\Request;

/**
 * Class SelectCategoriesRequest
 * @package App\Request
 */
class SelectCategoriesRequest
{
    private $categoriesId;

    /**
     * @return mixed
     */
    public function getCategoriesId()
    {
        return $this->categoriesId;
    }

    /**
     * @param mixed $categoriesId
     */
    public function setCategoriesId($categoriesId): void
    {
        $this->categoriesId = $categoriesId;
    }
}
