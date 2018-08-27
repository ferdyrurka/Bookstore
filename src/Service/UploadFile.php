<?php


namespace App\Service;

use App\Entity\ProductImage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class UploadFile
 * @package App\Service
 */
class UploadFile
{
    /**
     * @var string
     */
    private $path;

    /**
     * UploadFile constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * @param UploadedFile $file
     * @return ProductImage
     */
    public function upload(UploadedFile $file): ProductImage
    {
        $productImage = new ProductImage();
        $productImage->setHashName(hash('SHA1', $file->getClientOriginalName() . mt_rand(1, 99999)));
        $productImage->setExtension($file->guessExtension());
        $name = $productImage->getHashName() . '.' . $productImage->getExtension();
        $productImage->setPath('/img/product/' . $name);

        if (file_exists($this->path . $name)) {
            $productImage->setHashName(hash('SHA1', $file->getClientOriginalName() . mt_rand(10000, 999999)));
        }

        $file->move(
            $this->path,
            $name
        );

        return $productImage;
    }
}
