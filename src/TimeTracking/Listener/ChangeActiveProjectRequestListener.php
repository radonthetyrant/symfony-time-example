<?php
declare(strict_types=1);


namespace App\TimeTracking\Listener;


use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class ChangeActiveProjectRequestListener
{
    public function __construct(private SessionInterface $session)
    {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        if ($event->getRequest()->query->has('active_project')) {
            $this->session->set('active_project', $event->getRequest()->query->getInt('active_project', 1));
        }
    }
}