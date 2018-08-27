<?php

namespace App\Tests\Service;

use App\Service\UserAgent;
use PHPUnit\Framework\TestCase;
use \Mockery;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class UserAgentTest
 * @package App\Tests\Service
 */
class UserAgentTest extends TestCase
{

    use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    private $userAgent;
    private $request;

    public function setUp(): void
    {
        $this->request = Mockery::mock(RequestStack::class);
        $this->request->shouldReceive('getCurrentRequest')->once()->andReturn($this->request);
        $this->userAgent = new UserAgent($this->request);
        parent::setUp();
    }

    public function testGetSession(): void
    {
        $session = Mockery::mock(SessionInterface::class);
        $session->shouldReceive('isStarted')->times(2)->andReturn(true, false);
        $session->shouldReceive('getId')->times(2)->andReturn('testssessionid');
        $session->shouldReceive('start')->once();

        $this->request->shouldReceive('getSession')->times(2)->andReturn($session);

        $this->assertEquals('testssessionid', $this->userAgent->getSession());
        $this->assertEquals('testssessionid', $this->userAgent->getSession());
    }

    public function testGetIp(): void
    {
        $this->request->shouldReceive('getClientIp')->times(2)->andReturn('192.168.1.1', 'test');

        $this->assertEquals('192.168.1.1', $this->userAgent->getIp());

        $this->expectException(\Exception::class);
        $this->userAgent->getIp();
    }

    public function testGetUserAgent(): void
    {
        $header = Mockery::mock(HeaderBag::class);
        $header->shouldReceive('get')->once()->withArgs(array('User-Agent'))->andReturn('test " specialchars');
        $this->request->headers = $header;

        $this->assertEquals('test &quot; specialchars', $this->userAgent->getUserAgent());
    }
}
