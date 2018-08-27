<?php


namespace App\Repository;

use App\Entity\ForgotPassword;
use App\Exception\TokenNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class ForgotPasswordRepository
 * @package App\Repository
 */
class ForgotPasswordRepository extends ServiceEntityRepository
{

    /**
     * ForgotPasswordRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ForgotPassword::class);
    }

    /**
     * @param string $token
     * @return ForgotPassword
     */
    public function getOneByToken(string $token): ForgotPassword
    {
        $forgotPassword =  $this->findOneBy(array(
           'token' => $token
        ));

        if (!$forgotPassword) {
            throw new TokenNotFoundException('Does token not found');
        }

        return $forgotPassword;
    }

    /**
     * @param int $userId
     * @return ForgotPassword|null
     */
    public function findOneByUserId(int $userId): ?ForgotPassword
    {
        return  $this->findOneBy(array(
            'userId' => $userId
        ));
    }
}
