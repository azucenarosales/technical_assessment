<?php declare(strict_types=1);

namespace Application\Factory;

use Application\Service\CyberSourceService;
use Psr\Container\ContainerInterface;

class CyberSourceServiceFactory
{
    public function __invoke(ContainerInterface $container): CyberSourceService
    {
        $config = $container->get("config");
        return new CyberSourceService($config);
    }
}
