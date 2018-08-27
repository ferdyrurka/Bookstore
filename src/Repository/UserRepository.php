<?php


namespace App\Repository;

use App\Entity\User;
use App\Exception\EmailNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class UserRepository
 * @package App\Repository
 */
class UserRepository extends ServiceEntityRepository
{
    /**
     * UserRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @return array
     */
    public function findAll(): ?array
    {
        return parent::findAll();
    }

    /**
     * @param string $email
     * @return User
     */
    public function getOneByEmail(string $email): User
    {
        $user = $this->findOneBy(array(
            'email' => $email
        ));

        if (!$user) {
            throw new EmailNotFoundException('User not found!');
        }

        return $user;
    }

    /**
     * @param int $userId
     * @return User
     */
    public function getOneById(int $userId): User
    {
        $user = $this->find($userId);

        if (!$user) {
            throw new EmailNotFoundException('User not found!');
        }

        return $user;
    }
}
