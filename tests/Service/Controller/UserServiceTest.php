<?php

namespace App\Tests\Service\Controller;

use App\Entity\User;
use App\Form\Model\UpdateUserModel;
use App\Service\Controller\UserService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use \Mockery;

/**
 * Class UserServiceTest
 * @package App\Tests\Service
 */
class UserServiceTest extends TestCase
{
    use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    public function testSavePersonalDetails(): void
    {
        $em = Mockery::mock(EntityManagerInterface::class);
        $em->shouldReceive('persist')->withArgs(array(User::class))->once();
        $em->shouldReceive('flush')->once();
        $userService = new UserService($em);

        $user = Mockery::mock(User::class);
        $user->shouldReceive('setFirstName')->withArgs(array('First name'))->once();
        $user->shouldReceive('setSurname')->withArgs(array('Surname'))->once();
        $user->shouldReceive('setEmail')->withArgs(array('kontakt@lukaszstaniszewski.pl'))->once();

        $updateRequest = Mockery::mock(UpdateUserModel::class);
        $updateRequest->shouldReceive('getFirstName')->once()->andReturn('First name');
        $updateRequest->shouldReceive('getSurname')->once()->andReturn('Surname');
        $updateRequest->shouldReceive('getEmail')->once()->andReturn('kontakt@lukaszstaniszewski.pl');

        $this->assertNull($userService->savePersonalDetails($user, $updateRequest));
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
