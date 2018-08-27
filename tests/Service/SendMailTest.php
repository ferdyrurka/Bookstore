<?php

namespace App\Tests\Service;

use App\Model\SendMailForgotPassword;
use App\Service\SendMail;
use PHPUnit\Framework\TestCase;
use \Mockery;
use Twig\Environment;

/**
 * Class SendMailTest
 * @package App\Tests\Service
 */
class SendMailTest extends TestCase
{

    use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    public function testSendMail(): void
    {
        $sendMailInterface = new SendMailForgotPassword();
        $sendMailInterface->setTo('luksta556@gmail.com');
        $sendMailInterface->setAttributes(array(
            'token' => 'helloWorld'
        ));

        $twig = Mockery::mock(Environment::class);
        $twig->shouldReceive('render')->once()->andReturn('<html>Good</html>');

        $sendMail = new SendMail($twig);
        $this->assertTrue($sendMail->sendMail($sendMailInterface));
    }
}
