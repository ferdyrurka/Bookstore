<?php

namespace App\Event;

use App\Security\SessionAttackInterface;
use App\Service\SessionAttack;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class SessionAttackSubscriber
 * @package App\Event
 */
class SessionAttackSubscriber implements EventSubscriberInterface
{
    private $sessionAttack;

    /**
     * SessionAttackSubscriber constructor.
     * @param SessionAttack $sessionAttack
     */
    public function __construct(SessionAttack $sessionAttack)
    {
        $this->sessionAttack = $sessionAttack;
    }

    /**
     * @param FilterControllerEvent $event
     * @throws \Exception
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        if (!is_array($controller)) {
            return;
        }

        if (!$controller[0] instanceof SessionAttackInterface) {
            return;
        }


        if (!$this->sessionAttack->isYourSession()) {
            $redirectUrl = '/log-out';

            $event->setController(function () use ($redirectUrl) {
                return new RedirectResponse($redirectUrl);
            });
        }

        return;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents() : array
    {
        return array(
            KernelEvents::CONTROLLER => 'onKernelController',
        );
    }
}
