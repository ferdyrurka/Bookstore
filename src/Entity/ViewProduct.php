<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ViewProductRepository")
 * @ORM\Table(name="view_product")
 * @UniqueEntity("name")
 */
class ViewProduct
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=11)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", length=11, name="user_id", nullable=true)
     */
    private $userId;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private $productId;

    /**
     * @ORM\Column(type="string", length=14, name="user_ip")
     */
    private $userIp;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $device;

    /**
     * @return int
     */
    public function getId():int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * @param int|null $userId
     */
    public function setUserId(?int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return int
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * @param int $productId
     */
    public function setProductId(int $productId)
    {
        $this->productId = $productId;
    }

    /**
     * @return string
     */
    public function getUserIp(): string
    {
        return $this->userIp;
    }

    /**
     * @param string $userIp
     * @throws \Exception
     */
    public function setUserIp(string $userIp)
    {
        $userIp = filter_var($userIp, FILTER_VALIDATE_IP);
        if ($userIp == false) {
            throw  new \Exception('Failed filter a ip');
        }
        $this->userIp = $userIp;
    }

    /**
     * @return string
     */
    public function getDevice(): string
    {
        return $this->device;
    }

    /**
     * @param string $device
     */
    public function setDevice(string $device)
    {
        $this->device = $device;
    }
}
