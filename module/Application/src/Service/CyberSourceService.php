<?php

namespace Application\Service;

use Application\CyberSoapClient;
use \stdClass;

class CyberSourceService
{
    /** @var array  */
    private $config;
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function reversalRequest()
    {
        $client = $this->getCyberSoapClient();
        $referenceCode = uniqid();
        $request = $client->createRequest($referenceCode);

        $ccAuthReversalService = new stdClass();

        $ccAuthReversalService->run = 'true';
        $ccAuthReversalService->authRequestID = "6733977532346860703010"; //$reply->requestID;
        $ccAuthReversalService->orderRequestToken = "Axj/7wSTbQy2F4cEKg0iABsZYYsKTmHPtTLUpuojlQPMQUCURyoHmILpA+kEF4UMmkmXoxfT27wmSbaGWwvDghUGkQAA2SxH"; //$reply->requestToken;
        $request->ccAuthReversalService = $ccAuthReversalService;

        $purchaseTotals = new stdClass();
        $purchaseTotals->currency = 'USD';
        $purchaseTotals->grandTotalAmount = '210';
        $request->purchaseTotals = $purchaseTotals;

        $reply = $client->runTransaction($request);

        echo '<pre>';
        print("\nREVERSAL RESPONSE: " . print_r($reply, true));
        echo '<pre>';
    }

    public function refoundRequest()
    {
        $client = $this->getCyberSoapClient();
        $referenceCode = uniqid();
        $request = $client->createRequest($referenceCode);

        $voidService = new stdClass();

        $voidService->run = 'true';
        $voidService->voidRequestID = "6734013594476568503011"; //$reply->requestID;
        $voidService->voidRequestToken = "Axj//wSTbQ02NdfjPeLjABsQ3YNWThy5cKI5UEKRcAlEcqCFIuaQOnEC8KGTSTL0Yvp7dwwmSbaGmxrr8Z7xcYAA+TbO"; //$reply->requestToken;
        $request->voidService = $voidService;

        $purchaseTotals = new stdClass();
        $purchaseTotals->currency = 'USD';
        $purchaseTotals->grandTotalAmount = '100';
        $request->purchaseTotals = $purchaseTotals;

        $reply = $client->runTransaction($request);

        echo '<pre>';
        print("\nREFOUND RESPONSE: " . print_r($reply, true));
        echo '<pre>';

    }

    public function authorizationRequest()
    {
        $client = $this->getCyberSoapClient();
        $referenceCode = uniqid();
        $request = $client->createRequest($referenceCode);
        $request->billTo = $this->getBillTo();
        $request->card = $this->getCard();

        $purchaseTotals = new stdClass();
        $purchaseTotals->currency = 'USD';
        $request->purchaseTotals = $purchaseTotals;

        $item0 = new stdClass();
        $item0->unitPrice = '100';
        $item0->quantity = '2';
        $item0->id = '0';

        $item1 = new stdClass();
        $item1->unitPrice = '10';
        $item1->id = '1';

        $request->item = array($item0, $item1);
        $ccAuthService = new stdClass();
        $ccAuthService->run = 'true';
        $request->ccAuthService = $ccAuthService;

        $reply = $client->runTransaction($request);

        echo '<pre>';
        print("\nAUTH RESPONSE: " . print_r($reply, true));
        echo '<pre>';
    }

    public function authAndCaptureTwoRequests()
    {
        $client = $this->getCyberSoapClient();
        $referenceCode = uniqid();
        $request = $client->createRequest($referenceCode);
        $request->billTo = $this->getBillTo();
        $request->card = $this->getCard();

        $purchaseTotals = new stdClass();
        $purchaseTotals->currency = 'USD';
        $request->purchaseTotals = $purchaseTotals;

        $item0 = new stdClass();
        $item0->unitPrice = '100';
        $item0->quantity = '2';
        $item0->id = '0';

        $item1 = new stdClass();
        $item1->unitPrice = '10';
        $item1->id = '1';

        $request->item = array($item0, $item1);
        $ccAuthService = new stdClass();
        $ccAuthService->run = 'true';
        $request->ccAuthService = $ccAuthService;

        $reply = $client->runTransaction($request);

        echo '<pre>';
        print("\nAUTH RESPONSE: " . print_r($reply, true));

        if ($reply->decision != 'ACCEPT') {
            print("\nFailed auth request.\n");
            exit();
        }

        $ccCaptureService = new stdClass();
        $ccCaptureService->run = 'true';
        $ccCaptureService->authRequestID = $reply->requestID;

        $captureRequest = $client->createRequest($referenceCode);
        $captureRequest->ccCaptureService = $ccCaptureService;
        $captureRequest->item = array($item0, $item1);
        $captureReply = $client->runTransaction($captureRequest);
        print("\nCAPTURE RESPONSE: " . print_r($captureReply, true));

    }

    public function authAndCaptureOneRequest()
    {
        $client = $this->getCyberSoapClient();
        $referenceCode = uniqid();
        $request = $client->createRequest($referenceCode);

        $ccAuthService = new stdClass();
        $ccAuthService->run = 'true';
        $request->ccAuthService = $ccAuthService;

        $ccCaptureService = new stdClass();
        $ccCaptureService->run = 'true';
        $request->ccCaptureService = $ccCaptureService;

        $purchaseTotals = new stdClass();
        $purchaseTotals->currency = 'USD';
        $purchaseTotals->grandTotalAmount = '100';
        $request->purchaseTotals = $purchaseTotals;

        $request->billTo = $this->getBillTo();
        $request->card = $this->getCard();

        $reply = $client->runTransaction($request);

        echo '<pre>';
        print("\nRESPONSE: " . print_r($reply, true));
        echo '</pre>';
        exit();

    }

    /**
     * @return stdClass
     */
    private function getBillTo(): stdClass
    {
        $billTo = new stdClass();
        $billTo->firstName = 'John';
        $billTo->lastName = 'Doe';
        $billTo->street1 = 'Bosques de ANgola 78, Bosques de Aragon';
        $billTo->city = 'Mexico';
        $billTo->state = 'Mexico';
        $billTo->postalCode = '57170';
        $billTo->country = 'MX';
        $billTo->email = 'null@rejected.com';
        $billTo->ipAddress = '10.7.111.111';
        return $billTo;
    }

    /**
     * @return stdClass
     */
    private function getCard(): stdClass
    {
        $card = new stdClass();
        // $card->accountNumber = '4111111111111111';
        $card->accountNumber = '5555555555554444';
        $card->expirationMonth = '12';
        $card->expirationYear = '2024';
        return $card;
    }

    /**
     * @return CyberSoapClient
     * @throws \SoapFault
     */
    private function getCyberSoapClient(): CyberSoapClient
    {
        $client = new CyberSoapClient([], $this->config["cybersource"]);
        return $client;
    }

}