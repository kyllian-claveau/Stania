<?php

namespace App\Security\Listener;

use App\Business\DatabaseBusiness;
use App\Business\EnvironmentBusiness;
use App\Business\UpgradeBusiness;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\RouterInterface;

#[AsEventListener(event: 'kernel.request')]
class UpgradeListener
{
    public function __construct(
        private readonly UpgradeBusiness  $upgradeBusiness,
        private readonly RouterInterface  $router,
        private readonly DatabaseBusiness $databaseBusiness,
        private readonly Security         $security
    )
    {

    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if ($this->security->getFirewallConfig($event->getRequest())->getName() !== 'main') {
            return;
        }

        if (!$this->upgradeBusiness->needUpdate()) {
            return;
        }
        $currentRoute = $event->getRequest()->attributes->get('_route');
        if (!$this->databaseBusiness->canConnectToDatabase()) {
            if ($currentRoute !== 'app_upgrade_database') {
                $event->setResponse(new RedirectResponse($this->router->generate('app_upgrade_database')));
            }
            return;
        }

        $updateCode = $this->upgradeBusiness->getUpdateCode();

        if (null === $updateCode && $currentRoute !== 'app_upgrade_missing_update_code') {
            $event->setResponse(new RedirectResponse($this->router->generate('app_upgrade_missing_update_code')));
        } else if (null !== $updateCode && $currentRoute !== 'app_upgrade_database') {
            $event->setResponse(new RedirectResponse($this->router->generate('app_upgrade_database')));
        }
    }
}