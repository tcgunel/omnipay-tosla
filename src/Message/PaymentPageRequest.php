<?php

namespace Omnipay\Tosla\Message;

use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Tosla\Models\EnrolmentRequestModel;
use Omnipay\Tosla\Models\PaymentPageRequestModel;
use Omnipay\Tosla\Models\PurchaseRequestModel;
use Omnipay\Tosla\Traits\PurchaseGettersSetters;

class PaymentPageRequest extends RemoteAbstractRequest
{
    use PurchaseGettersSetters;

    public $endpoint = '/threeDPayment';

    /**
     * @return PaymentPageRequestModel
     *
     * @throws InvalidCreditCardException
     * @throws InvalidRequestException
     */
    public function getData(): PaymentPageRequestModel
    {
        $this->validate(
            'clientId',
            'apiUser',
            'apiPass',

            'returnUrl',
            'amount',
            'currency',
        );

        ini_set('date.timezone', 'Europe/Istanbul');

        $rnd = random_int(1, 10000);
        $time = date('YmdHis');

        $PaymentPageRequestModel = new PaymentPageRequestModel([
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

        $PaymentPageRequestModel->hash = $this->hash($PaymentPageRequestModel, $this->getApiPass());

        return $PaymentPageRequestModel;
    }

    private function hash(PaymentPageRequestModel $PaymentPageRequestModel, string $apiPass): string
    {
        $clientId = $PaymentPageRequestModel->clientId;
        $apiUser = $PaymentPageRequestModel->apiUser;
        $rnd = $PaymentPageRequestModel->rnd;
        $timeSpan = $PaymentPageRequestModel->timeSpan;

        $hashString = $apiPass.$clientId.$apiUser.$rnd.$timeSpan;

        $hashing_bytes = hash('sha512', ($hashString), true);

        return base64_encode($hashing_bytes);
    }

    protected function createResponse($data): PaymentPageResponse
    {
        return $this->response = new PaymentPageResponse($this, $data);
    }

    /**
     * @param  PaymentPageRequestModel  $data
     * @return PaymentPageResponse
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
