<?php

namespace Omnipay\Tosla\Message;

use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Tosla\Models\ChargeRequestModel;
use Omnipay\Tosla\Traits\PurchaseGettersSetters;

class ChargeRequest extends RemoteAbstractRequest
{
    use PurchaseGettersSetters;

    protected $endpoint = '/Payment';

    /**
     * @return ChargeRequestModel
     *
     * @throws InvalidCreditCardException
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate(
            'clientId',
            'apiUser',
            'apiPass',

            'amount',
            'currency',
        );

        $this->getCard()->validate();

        ini_set('date.timezone', 'Europe/Istanbul');

        $rnd = $this->getRnd() ?? random_int(1, 10000);
        $time = $this->getTimeSpan() ?? date('YmdHis');

        $purchaseRequestModel = [
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
        ];

        $purchaseRequestModel['hash'] = $this->hash($purchaseRequestModel, $this->getApiPass());

        return new ChargeRequestModel(array_merge([
            'cardHolderName' => $this->getCard()->getName(),
            'cardNo' => $this->getCard()->getNumber(),
            'expireDate' => $this->getCard()->getExpiryDate('my'),
            'cvv' => $this->getCard()->getCvv(),
        ], $purchaseRequestModel));
    }

    private function hash(array $purchaseRequestModel, string $apiPass): string
    {
        $clientId = $purchaseRequestModel['clientId'];
        $apiUser = $purchaseRequestModel['apiUser'];
        $rnd = $purchaseRequestModel['rnd'];
        $timeSpan = $purchaseRequestModel['timeSpan'];

        $hashString = $apiPass.$clientId.$apiUser.$rnd.$timeSpan;

        $hashing_bytes = hash('sha512', ($hashString), true);

        return base64_encode($hashing_bytes);
    }

    protected function createResponse($data): ChargeResponse
    {
        return $this->response = new ChargeResponse($this, $data);
    }

    /**
     * @param  ChargeRequestModel  $data
     * @return ChargeResponse
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
