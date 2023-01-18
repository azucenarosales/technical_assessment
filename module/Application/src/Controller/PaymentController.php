<?php

namespace Application\Controller;

use Application\Service\CyberSourceService;
use Laminas\Mvc\Controller\AbstractActionController;
use \stdClass;

class PaymentController extends AbstractActionController
{

    /** @var array  */
    private $cyberService;
    public function __construct(CyberSourceService $cyberService)
    {
        $this->cyberService = $cyberService;
    }

    public function indexAction()
    {
        $this->cyberService->authAndCaptureOneRequest();
        $this->cyberService->authAndCaptureTwoRequests();
        $this->cyberService->authorizationRequest();
        $this->cyberService->reversalRequest();
        $this->cyberService->refoundRequest();
        exit();
    }
}