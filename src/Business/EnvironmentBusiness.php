<?php

namespace App\Business;

use Symfony\Component\HttpKernel\KernelInterface;

class EnvironmentBusiness
{
    public const DEV_ENVIRONMENT = 'dev';
    public const PROD_ENVIRONMENT = 'prod';
    public const TEST_ENVIRONMENT = 'test';

    public function __construct(private readonly KernelInterface $kernel)
    {

    }

    public function getCurrentEnvironment(): ?string
    {
        return $this->kernel->getEnvironment();
    }

    public function isCurrentEnvironment(string $environmentName): bool
    {
        return false;
        return $this->getCurrentEnvironment() === $environmentName;
    }
}