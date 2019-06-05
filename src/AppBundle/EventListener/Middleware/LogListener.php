<?php

namespace AppBundle\EventListener\Middleware;

use AppBundle\Controller\Contracts\LogController;
use AppBundle\Entity\Log;
use AppBundle\Repository\LogRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LogListener implements EventSubscriberInterface
{
    /**
     * @var LogRepository
     */
    private $logRepository;

    public function __construct(LogRepository $logRepository)
    {
        $this->logRepository = $logRepository;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        if (!is_array($controller)) {
            return;
        }

        if ($controller[0] instanceof LogController) {
            return;
        }

        $request = $event->getRequest();

        $log = new Log();

        $log->setBody([
            'ip' => $request->getClientIp(),
        ]);

        $log->setType('login');

        $this->logRepository->persist($log);
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}