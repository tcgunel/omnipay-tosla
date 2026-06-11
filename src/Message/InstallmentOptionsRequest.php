<?php

namespace Omnipay\Tosla\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Tosla\Models\InstallmentOptionsRequestModel;
use Omnipay\Tosla\Traits\PurchaseGettersSetters;

/**
 * Taksit ve Taksitlere Karşılık Gelen Tutar Bilgisi (GetInstallmentOptions).
 *
 * isCommission = 1 gönderilecek taksitli işlemlerde, verilen tutara karşılık
 * gelen her taksitteki toplam tutar (totalAmount) bu servisten alınır.
 */
class InstallmentOptionsRequest extends RemoteAbstractRequest
{
    use PurchaseGettersSetters;

    protected $endpoint = '/GetInstallmentOptions';

    /**
     * @return InstallmentOptionsRequestModel
     *
     * @throws InvalidRequestException
     */
    public function getData(): InstallmentOptionsRequestModel
    {
        $this->validate(
            'clientId',
            'apiUser',
            'apiPass',
            'amount',
        );

        ini_set('date.timezone', 'Europe/Istanbul');

        $rnd = substr(sha1(mt_rand()), 0, 24);
        $time = date('YmdHis');

        // The amount is taken in major units and converted to kuruş by the
        // model's format_amount helper, so this endpoint does not depend on a
        // currency being set (unlike Omnipay's getAmount()).
        $InstallmentOptionsRequestModel = new InstallmentOptionsRequestModel([
            'clientId' => $this->getClientId(),
            'apiUser' => $this->getApiUser(),
            'rnd' => $rnd,
            'timeSpan' => $time,
            'amount' => $this->getParameter('amount'),
        ]);

        $InstallmentOptionsRequestModel->hash = $this->hash($InstallmentOptionsRequestModel, $this->getApiPass());

        return $InstallmentOptionsRequestModel;
    }

    private function hash(InstallmentOptionsRequestModel $model, string $apiPass): string
    {
        $hashString = $apiPass . $model->clientId . $model->apiUser . $model->rnd . $model->timeSpan;

        $hashing_bytes = hash('sha512', ($hashString), true);

        return base64_encode($hashing_bytes);
    }

    /**
     * @throws \JsonException
     */
    protected function createResponse($data): InstallmentOptionsResponse
    {
        return $this->response = new InstallmentOptionsResponse($this, $data);
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
