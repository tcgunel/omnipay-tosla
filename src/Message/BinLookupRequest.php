<?php

namespace Omnipay\Tosla\Message;

use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Tosla\Models\BinLookupRequestModel;

class BinLookupRequest extends RemoteAbstractRequest
{
    protected $endpoint = '/GetCommissionAndInstallmentInfo';

    /**
     * @return BinLookupRequestModel
     *
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     * @throws InvalidCreditCardException
     */
    public function getData()
    {
        $this->validateAll();

        ini_set('date.timezone', 'Europe/Istanbul');

        $rnd = substr(sha1(mt_rand()), 0, 24);
        $time = date('YmdHis');

        $BinLookupRequestModel = new BinLookupRequestModel([
            'ClientId' => $this->getClientId(),
            'ApiUser' => $this->getApiUser(),
            'Rnd' => $rnd,
            'TimeSpan' => $time,
            'Bin' => $this->getCard()->getNumber(),
        ]);

        $BinLookupRequestModel->Hash = $this->hash($BinLookupRequestModel, $this->getApiPass());

        return $BinLookupRequestModel;
    }

    private function hash(BinLookupRequestModel $purchaseRequestModel, string $apiPass): string
    {
        $clientId = $purchaseRequestModel->ClientId;
        $apiUser = $purchaseRequestModel->ApiUser;
        $rnd = $purchaseRequestModel->Rnd;
        $timeSpan = $purchaseRequestModel->TimeSpan;

        $hashString = $apiPass.$clientId.$apiUser.$rnd.$timeSpan;

        $hashing_bytes = hash('sha512', ($hashString), true);

        return base64_encode($hashing_bytes);
    }

    /**
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     * @throws InvalidCreditCardException
     */
    protected function validateAll(): void
    {
        if (empty($this->getCard()->getNumber())) {
            throw new InvalidCreditCardException('Card number is required');
        }

        if (! is_null($this->getCard()->getNumber()) && ! preg_match('/^\d{6,19}$/', $this->getCard()->getNumber())) {
            throw new InvalidCreditCardException('Card number should have at least 6 to maximum of 19 digits');
        }
    }

    /**
     * @throws \JsonException
     */
    protected function createResponse($data): BinLookupResponse
    {
        return $this->response = new BinLookupResponse($this, $data);
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
