<?php


namespace App\Repository;

use App\Entity\UserSession;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class UserSessionRepository
 * @package App\Repository
 */
class UserSessionRepository extends ServiceEntityRepository
{
    /**
     * UserSessionRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserSession::class);
    }

    /**
     * @param string $session
     * @return UserSession|null
     */
    public function findOneBySession(string $session): ?UserSession
    {
        return $this->findOneBy(array(
           'session' => $session
        ));
    }
}
