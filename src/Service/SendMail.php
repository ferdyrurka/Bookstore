<?php


namespace App\Service;

use App\Model\SendMailInterface;
use Twig\Environment as Twig;

/**
 * Class SendMail
 * @package App\Service
 */
class SendMail
{

    /**
     * @var Twig
     */
    private $twig;

    /**
     * SendMail constructor.
     * @param Twig $twig
     */
    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param string $pathTemplate
     * @param array|null $attr
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    private function renderForm(string $pathTemplate, ?array $attr): string
    {
        if (is_null($attr)) {
            $attr = array();
        }

        return $this->twig->render($pathTemplate, $attr);
    }

    /**
     * @param SendMailInterface $sendMail
     * @return bool
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function sendMail(SendMailInterface $sendMail): bool
    {
        return mail(
            $sendMail->getTo(),
            $sendMail->getSubject(),
            $this->renderForm($sendMail->getPathTemplate(), $sendMail->getAttributes()),
            'MIME-Version: 1.0' . "\r\n" .
            'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
            'From: ' . $sendMail->getFrom() . "\r\n" .
            'Reply-To: ' . $sendMail->getFrom() . "\r\n" .
            'X-Mailer: PHP/' . phpversion()
        );
    }
}
