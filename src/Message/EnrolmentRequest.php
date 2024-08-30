<?php

namespace Omnipay\Tosla\Message;

use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Tosla\Models\EnrolmentRequestModel;
use Omnipay\Tosla\Models\PurchaseRequestModel;
use Omnipay\Tosla\Traits\PurchaseGettersSetters;

class EnrolmentRequest extends RemoteAbstractRequest
{
    use PurchaseGettersSetters;

    public $endpoint = '/threeDPayment';

    /**
     * @return EnrolmentRequestModel
     *
     * @throws InvalidCreditCardException
     * @throws InvalidRequestException
     */
    public function getData(): EnrolmentRequestModel
    {
        $this->validate(
            'clientId',
            'apiUser',
            'apiPass',

            'returnUrl',
            'amount',
            'currency',
        );

        $this->getCard()->validate();

        ini_set('date.timezone', 'Europe/Istanbul');

        $rnd = random_int(1, 10000);
        $time = date('YmdHis');

        $EnrolmentRequestModel = new EnrolmentRequestModel([
            'clientId' => $this->getClientId(),
            'apiUser' => $this->getApiUser(),
            'rnd' => $rnd,
            'timeSpan' => $time,
            'callbackUrl' => $this->getReturnUrl(),
            'isCommission' => $this->getHasInstallmentComission() ?? 0,
            'orderId' => $this->getTransactionId(),
            'amount' => $this->getAmount(),
            'currency' => $this->getCurrency(),
            'installmentCount' => $this->getInstallment(),
            'description' => $this->getDescription(),
            'echo' => $this->getEcho(),
            'extraParameters' => $this->getExtraParameters(),
        ]);

        $EnrolmentRequestModel->hash = $this->hash($EnrolmentRequestModel, $this->getApiPass());

        return $EnrolmentRequestModel;
    }

    private function hash(EnrolmentRequestModel $EnrolmentRequestModel, string $apiPass): string
    {
        $clientId = $EnrolmentRequestModel->clientId;
        $apiUser = $EnrolmentRequestModel->apiUser;
        $rnd = $EnrolmentRequestModel->rnd;
        $timeSpan = $EnrolmentRequestModel->timeSpan;

        $hashString = $apiPass.$clientId.$apiUser.$rnd.$timeSpan;

        $hashing_bytes = hash('sha512', ($hashString), true);

        return base64_encode($hashing_bytes);
    }

    protected function createResponse($data): EnrolmentResponse
    {
        return $this->response = new EnrolmentResponse($this, $data);
    }

    /**
     * @param  PurchaseRequestModel  $data
     * @return EnrolmentResponse
     *
     * @throws \JsonException
     */
    public function sendData($data)
    {
        $httpResponse = $this->httpClient->request(
            'POST',
            $this->getEndpoint(),
            [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            json_encode($data, JSON_THROW_ON_ERROR)
        );

        return $this->createResponse($httpResponse);
    }
}
