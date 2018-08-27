<?php

namespace App\Tests\Service;

use App\Entity\ForgotPassword;
use App\Entity\User;
use App\Exception\TokenNotFoundException;
use App\Repository\ForgotPasswordRepository;
use App\Request\ChangePasswordUserRequest;
use App\Request\NewPasswordUserRequest;
use App\Service\ChangePasswordService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use \Mockery;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class ChangePasswordServiceTest
 * @package App\Tests\Service
 */
class ChangePasswordServiceTest extends TestCase
{

    use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    private $em;
    private $encoder;
    private $changePasswordService;

    public function setUp(): void
    {
        $this->em = Mockery::mock(EntityManagerInterface::class);

        $this->encoder = Mockery::mock(UserPasswordEncoderInterface::class);

        $this->changePasswordService = new ChangePasswordService($this->em, $this->encoder);
        parent::setUp();
    }

    public function testSaveChangePassword(): void
    {
        $this->em->shouldReceive('persist')->withArgs(array(User::class))->once();
        $this->em->shouldReceive('flush')->once();

        $this->encoder
            ->shouldReceive('encodePassword')
            ->withArgs(array(User::class, 'plain_password'))
            ->once()
            ->andReturn('hash_password')
        ;

        $this->changePasswordService = new ChangePasswordService($this->em, $this->encoder);

        $user = Mockery::mock(User::class);
        $user->shouldReceive('setPassword')->withArgs(array('hash_password'))->once();

        $passwordUserRequest = Mockery::mock(ChangePasswordUserRequest::class);
        $passwordUserRequest->shouldReceive('getPlainPassword')->once()->andReturn('plain_password');

        $this->changePasswordService->saveChangePassword($user, $passwordUserRequest);
    }

    public function testSaveNewPassword(): void
    {
        $this->em->shouldReceive('remove')->withArgs(array(ForgotPassword::class))->once();
        $this->em->shouldReceive('persist')->withArgs(array(User::class))->once();
        $this->em->shouldReceive('flush')->once();

        $this->encoder
            ->shouldReceive('encodePassword')
            ->withArgs(array(User::class, 'plain_password'))
            ->once()
            ->andReturn('hash_password')
        ;

        $newPasswordUserRequest = Mockery::mock(NewPasswordUserRequest::class);
        $newPasswordUserRequest->shouldReceive('getPlainPassword')->once()->andReturn('plain_password');

        $user = Mockery::mock(User::class);
        $user->shouldReceive('setPassword')
            ->withArgs(array('hash_password'))
            ->once()
        ;

        $forgotPassword = Mockery::mock(ForgotPassword::class);
        $forgotPassword->shouldReceive('getUserReferences')->once()->andReturn($user);

        $this->assertNull($this->changePasswordService->saveNewPassword($forgotPassword, $newPasswordUserRequest));
    }

    public function testGetToken(): void
    {
        $forgotPassword = Mockery::mock(ForgotPassword::class);
        $time = new \DateTime("+1 hour");
        $time->setTimezone(new \DateTimeZone('Europe/Warsaw'));

        $forgotPassword->shouldReceive('getTimeCreate')->once()->andReturn($time->format('Y-m-d H:i:s'));


        $forgotPasswordRepository = Mockery::mock(ForgotPasswordRepository::class);
        $forgotPasswordRepository
            ->shouldReceive('getOneByToken')
            ->withArgs(array('token'))
            ->once()
            ->andReturn($forgotPassword)
        ;

        $result = $this->changePasswordService->getToken('token', $forgotPasswordRepository);
        $this->assertInstanceOf(ForgotPassword::class, $result);

        $time = new \DateTime("-1 hour");
        $forgotPassword->shouldReceive('getTimeCreate')->once()->andReturn($time->format('Y-m-d H:i:s'));

        $forgotPasswordRepository
            ->shouldReceive('getOneByToken')
            ->withArgs(array('token'))
            ->once()
            ->andReturn($forgotPassword)
        ;

        $this->em->shouldReceive('remove')->withArgs(array(ForgotPassword::class))->once();
        $this->em->shouldReceive('flush')->once();

        $this->expectException(TokenNotFoundException::class);
        $this->changePasswordService->getToken('token', $forgotPasswordRepository);
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
