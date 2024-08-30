<?php

namespace Omnipay\Tosla\Message;

use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Tosla\Helpers\Helper;
use Omnipay\Tosla\Models\BinLookupRequestModel;
use Omnipay\Tosla\Models\HistoryRequestModel;

class HistoryRequest extends RemoteAbstractRequest
{
    protected $endpoint = '/history';

    /**
     * @return HistoryRequestModel
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

            'transactionDate',
            'page',
            'pageSize',
        );

        ini_set('date.timezone', 'Europe/Istanbul');

        $rnd = substr(sha1(mt_rand()), 0, 24);
        $time = date('YmdHis');

        $HistoryRequestModel = new HistoryRequestModel([
            'clientId' => $this->getClientId(),
            'apiUser' => $this->getApiUser(),
            'rnd' => $rnd,
            'timeSpan' => $time,
            'orderId' => $this->getOrderId(),
            'transactionDate' => $this->getTransactionDate()?->format('Ymd'),
            'page' => $this->getPage(),
            'pageSize' => $this->getPageSize(),
        ]);

        $HistoryRequestModel->hash = $this->hash($HistoryRequestModel, $this->getApiPass());

        return $HistoryRequestModel;
    }

    private function hash(HistoryRequestModel $purchaseRequestModel, string $apiPass): string
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
    protected function createResponse($data): HistoryResponse
    {
        return $this->response = new HistoryResponse($this, $data);
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
