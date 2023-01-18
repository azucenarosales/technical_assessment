<?php declare(strict_types=1);

namespace Application\Factory;

use Application\Controller\PaymentController;
use Application\Service\CyberSourceService;
use Psr\Container\ContainerInterface;

class PaymentControllerFactory
{
    public function __invoke(ContainerInterface $container): PaymentController
    {
        $cyber = $container->get(CyberSourceService::class);

        return new PaymentController($cyber);
    }
}
