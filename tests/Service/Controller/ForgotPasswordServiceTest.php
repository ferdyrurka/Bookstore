<?php

namespace App\Tests\Service\Controller;

use App\Entity\ForgotPassword;
use App\Entity\User;
use App\Model\SendMailForgotPassword;
use App\Repository\ForgotPasswordRepository;
use App\Repository\UserRepository;
use App\Request\ForgotPasswordRequest;
use App\Service\Controller\ForgotPasswordService;
use App\Service\SendMail;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use \Mockery;
use \Exception;

/**
 * Class ForgotPasswordServiceTest
 * @package App\Tests\Service
 */
class ForgotPasswordServiceTest extends TestCase
{
    use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    /**
     * @throws Exception
     */
    public function testCreateForgotPassword(): void
    {
        $em = Mockery::mock(EntityManagerInterface::class);

        $em->shouldReceive('persist')->times(2)->withArgs(array(ForgotPassword::class));
        $em->shouldReceive('flush')->times(2);

        $user = Mockery::mock(User::class);
        $user->shouldReceive('getId')->times(2)->andReturn(1);
        $user->shouldReceive('getRoles')->times(3)
            ->andReturn(array('ROLE_USER'), array('ROLE_USER'), array('ROLE_ADMIN'));

        $userRepository = Mockery::mock(UserRepository::class);
        $userRepository
            ->shouldReceive('getOneByEmail')
            ->times(3)
            ->withArgs(array('kontakt@lukaszstaniszewski.pl'))
            ->andReturn($user)
        ;

        $forgotPasswordRequest = Mockery::mock(ForgotPasswordRequest::class);
        $forgotPasswordRequest->shouldReceive('getEmail')->times(5)->andReturn('kontakt@lukaszstaniszewski.pl');

        $forgotPassword = Mockery::mock(ForgotPassword::class);
        $forgotPassword->shouldReceive('setUserReferences')->withArgs(array(User::class))->once();
        $forgotPassword->shouldReceive('setTimeCreate')->once();
        $forgotPassword->shouldReceive('getToken')->once()->andReturn('HelloWorld');

        $forgotPasswordRepository = Mockery::mock(ForgotPasswordRepository::class);
        $forgotPasswordRepository
            ->shouldReceive('findOneByUserId')
            ->withArgs(array(1))
            ->times(2)
            ->andReturn(null, $forgotPassword)
        ;

        $sendMail = Mockery::mock(SendMail::class);
        $sendMail->shouldReceive('sendMail')->times(2)->withArgs([SendMailForgotPassword::class])->andReturn(true);

        $forgotPasswordService = new ForgotPasswordService($em, $userRepository, $forgotPasswordRepository, $sendMail);
        $this->assertNull($forgotPasswordService->createForgotPassword($forgotPasswordRequest));

        $this->assertNull($forgotPasswordService->createForgotPassword($forgotPasswordRequest));

        $this->expectException(Exception::class);
        $this->assertNull($forgotPasswordService->createForgotPassword($forgotPasswordRequest));
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
