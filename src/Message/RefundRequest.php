<?php

namespace Omnipay\Tosla\Message;

use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Tosla\Helpers\Helper;
use Omnipay\Tosla\Models\BinLookupRequestModel;
use Omnipay\Tosla\Models\RefundRequestModel;
use Omnipay\Tosla\Models\VoidRequestModel;

class RefundRequest extends RemoteAbstractRequest
{
    protected $endpoint = '/refund';

    /**
     * @return RefundRequestModel
     *
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     * @throws InvalidCreditCardException
     */
    public function getData()
    {
        $this->validate(
            'clientId',
            'apiUser',
            'apiPass',

            'orderId',
            'amount',
        );

        ini_set('date.timezone', 'Europe/Istanbul');

        $rnd = substr(sha1(mt_rand()), 0, 24);
        $time = date('YmdHis');

        $RefundRequestModel = new RefundRequestModel([
            'clientId' => $this->getClientId(),
            'apiUser' => $this->getApiUser(),
            'rnd' => $rnd,
            'timeSpan' => $time,
            'orderId' => $this->getOrderId(),
            'amount' => $this->getAmount(),
            'echo' => $this->getEcho(),
        ]);

        $RefundRequestModel->hash = $this->hash($RefundRequestModel, $this->getApiPass());

        return $RefundRequestModel;
    }

    private function hash(RefundRequestModel $purchaseRequestModel, string $apiPass): string
    {
        $clientId = $purchaseRequestModel->clientId;
        $apiUser = $purchaseRequestModel->apiUser;
        $rnd = $purchaseRequestModel->rnd;
        $timeSpan = $purchaseRequestModel->timeSpan;

        $hashString = $apiPass.$clientId.$apiUser.$rnd.$timeSpan;

        $hashing_bytes = hash('sha512', ($hashString), true);

        return base64_encode($hashing_bytes);
    }


    /**
     * @throws \JsonException
     */
    protected function createResponse($data): RefundResponse
    {
        return $this->response = new RefundResponse($this, $data);
    }

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