<?php

namespace App\Tests\Service;

use App\Entity\UserSession;
use App\Repository\UserSessionRepository;
use App\Service\UserAgent;
use App\Service\SessionAttack;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use \Mockery;

/**
 * Class SessionAttackTest
 * @package App\Tests\Service
 */
class SessionAttackTest extends TestCase
{
    use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    /**
     * @throws \Exception
     */
    public function testCheckUser(): void
    {
        $userAgent = Mockery::mock(UserAgent::class);
        $userSessionRepository = Mockery::mock(UserSessionRepository::class);
        $em = Mockery::mock(EntityManagerInterface::class);

        $sessionAttack = new SessionAttack($userAgent, $userSessionRepository, $em);

        $userAgent->shouldReceive('getSession')->times(3)->andReturn('', 'hello world', 'Hello world');
        $userAgent->shouldReceive('getUserAgent')->times(2)->andReturn('Good', 'fail');
        $userAgent->shouldReceive('getIp')->once()->andReturn('192.168.1.1');

        $userSession = new UserSession();
        $userSession->setUserIp('192.168.1.1');
        $userSession->setUserAgent('Good');

        $userSessionRepository->shouldReceive('findOneBySession')->times(2)->andReturn(null, $userSession);

        $em->shouldReceive('persist')->once()->withArgs(array(UserSession::class));
        $em->shouldReceive('flush')->once();

        $this->assertTrue($sessionAttack->isYourSession());
        $this->assertTrue($sessionAttack->isYourSession());
        $this->assertFalse($sessionAttack->isYourSession());
    }
}
