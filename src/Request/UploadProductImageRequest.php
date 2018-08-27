<?php


namespace App\Request;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class UploadProductImageRequest
 * @package App\Request
 */
class UploadProductImageRequest
{
    /**
     * @Assert\Image(
     *     mimeTypes={"image/jpeg","image/png","image/jpg"},
     *     mimeTypesMessage="The possible file extension is jpg, jpeg, png.",
     *     minWidth=200,
     *     maxWidth=1920,
     *     minHeight=200,
     *     maxHeight=1280,
     * )
     */
    private $productImage;

    /**
     * @return null|UploadedFile
     */
    public function getProductImage(): ?UploadedFile
    {
        return $this->productImage;
    }

    /**
     * @param null|UploadedFile $productImage
     */
    public function setProductImage(?UploadedFile $productImage): void
    {
        $this->productImage = $productImage;
    }
}
