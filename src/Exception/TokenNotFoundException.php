<?php


namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ForgotPasswordTokenNotFoundException
 * @package App\Exception
 */
class TokenNotFoundException extends NotFoundHttpException
{

}
