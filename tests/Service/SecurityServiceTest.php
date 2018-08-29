<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Request\CreateUserRequest;
use App\Service\SecurityService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use \Mockery;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class SecurityServiceTest
 * @package App\Tests\Service
 */
class SecurityServiceTest extends TestCase
{
    use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    public function testSaveUser(): void
    {
        $user = Mockery::mock(User::class);

        $user->shouldReceive('getPlainPassword')->once()->andReturn('password_plain');
        $user->shouldReceive('setRoles')->once()->withArgs(array('ROLE_USER'));
        $user->shouldReceive('setStatus')->once()->withArgs(array(1));
        $user->shouldReceive('setPassword')->once();

        $userPasswordEncoder = Mockery::mock(UserPasswordEncoderInterface::class);

        $userPasswordEncoder
            ->shouldReceive('encodePassword')
            ->withArgs(array(
                User::class,
                'password_plain'
            ))->andReturn('hash_password');

        $em = Mockery::mock(EntityManagerInterface::class);

        $em->shouldReceive('persist')->withArgs(array(User::class))->once();
        $em->shouldReceive('flush')->once();

        $securityService = new SecurityService($userPasswordEncoder, $em);
        $this->assertNull($securityService->saveUser($user, 'ROLE_USER'));
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
