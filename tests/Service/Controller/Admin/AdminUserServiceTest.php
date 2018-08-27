<?php

namespace App\Tests\Service\Controller\Admin;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Controller\Admin\AdminUserService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use \Mockery;

/**
 * Class AdminUserServiceTest
 * @package App\Tests\Service\Admin
 */
class AdminUserServiceTest extends TestCase
{
    use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    private $em;
    private $userRepository;
    private $adminUserService;

    public function setUp(): void
    {
        parent::setUp();
        $this->em = Mockery::mock(EntityManagerInterface::class);
        $this->userRepository = Mockery::mock(UserRepository::class);

        $this->adminUserService = new AdminUserService($this->userRepository, $this->em);
    }

    public function testGetAll(): void
    {
        $this->userRepository->shouldReceive('findAll')->times(2)->andReturn(null, array(new User()));

        $result = $this->adminUserService->getAll();
        $this->assertEmpty($result);
        $this->assertTrue(is_array($result));

        $result = $this->adminUserService->getAll();
        $this->assertNotEmpty($result);
        $this->assertTrue(is_array($result));
    }

    public function testDeleteAccount(): void
    {
        $this->userRepository->shouldReceive('getOneById')->withArgs(array(1))->once()->andReturn(new User());

        $this->em->shouldReceive('remove')->withArgs(array(User::class))->once();
        $this->em->shouldReceive('flush')->once();

        $this->assertNull($this->adminUserService->deleteAccount(1));
    }

    /**
     * @param int $status
     */
    public function activateOrDisableAccount(int $status): void
    {
        $user = Mockery::mock(User::class);
        $user->shouldReceive('setStatus')->withArgs(array($status))->once();

        $this->userRepository->shouldReceive('getOneById')->withArgs(array(2))->once()->andReturn($user);

        $this->em->shouldReceive('persist')->withArgs(array(User::class))->once();
        $this->em->shouldReceive('flush')->once();
    }

    public function testDisableAccount(): void
    {
        $this->activateOrDisableAccount(0);
        $this->assertNull($this->adminUserService->disableAccount(2));
    }

    public function testActivateAccount(): void
    {
        $this->activateOrDisableAccount(1);
        $this->assertNull($this->adminUserService->activateAccount(2));
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
