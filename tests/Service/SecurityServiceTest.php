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
        $createUserRequest = Mockery::mock(CreateUserRequest::class);

        $createUserRequest->shouldReceive('getUsername')->once()->andReturn('Username');
        $createUserRequest->shouldReceive('getEmail')->once()->andReturn('kontakt@lukaszstaniszewski.pl');
        $createUserRequest->shouldReceive('getFirstName')->once()->andReturn('First name');
        $createUserRequest->shouldReceive('getSurname')->once()->andReturn('Surname');
        $createUserRequest->shouldReceive('getPlainPassword')->once()->andReturn('password_plain');

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
        $this->assertNull($securityService->saveUser($createUserRequest, 'ROLE_USER'));
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
