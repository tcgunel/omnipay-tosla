<?php

namespace Omnipay\Tosla\Message;

use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Tosla\Models\BinLookupRequestModel;
use Omnipay\Tosla\Models\VoidRequestModel;

class VoidRequest extends RemoteAbstractRequest
{
    protected $endpoint = '/void';

    /**
     * @return VoidRequestModel
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
        );

        ini_set('date.timezone', 'Europe/Istanbul');

        $rnd = substr(sha1(mt_rand()), 0, 24);
        $time = date('YmdHis');

        $VoidRequestModel = new VoidRequestModel([
            'clientId' => $this->getClientId(),
            'apiUser' => $this->getApiUser(),
            'rnd' => $rnd,
            'timeSpan' => $time,
            'orderId' => $this->getOrderId(),
            'echo' => $this->getEcho(),
        ]);

        $VoidRequestModel->hash = $this->hash($VoidRequestModel, $this->getApiPass());

        return $VoidRequestModel;
    }

    private function hash(VoidRequestModel $purchaseRequestModel, string $apiPass): string
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
    protected function createResponse($data): VoidResponse
    {
        return $this->response = new VoidResponse($this, $data);
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
