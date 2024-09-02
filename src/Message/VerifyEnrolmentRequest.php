<?php

namespace Omnipay\Tosla\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Tosla\Models\RequestHeadersModel;
use Omnipay\Tosla\Models\VerifyEnrolmentRequestModel;
use Omnipay\Tosla\Traits\PurchaseGettersSetters;

class VerifyEnrolmentRequest extends AbstractRequest
{
    use PurchaseGettersSetters;

    /**
     * @return VerifyEnrolmentRequestModel
     *
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     * @throws \Omnipay\Common\Exception\InvalidCreditCardException
     */
    public function getData()
    {
        $this->validate(
            'apiUser',
            'apiPass',

            'orderId',
            'mdStatus',
            'threeDSessionId',
            'bankResponseCode',
            'requestStatus',
            'hashParameters',
            'hash',
        );

        return new VerifyEnrolmentRequestModel([
            'ApiUser' => $this->getApiUser(),
            'ClientId' => $this->getClientId(),
            'OrderId' => $this->getOrderId(),
            'MdStatus' => $this->getMdStatus(),
            'ThreeDSessionId' => $this->getThreeDSessionId(),
            'BankResponseCode' => $this->getBankResponseCode(),
            'BankResponseMessage' => $this->getBankResponseMessage(),
            'RequestStatus' => $this->getRequestStatus(),
            'HashParameters' => $this->getHashParameters(),
            'Hash' => $this->getHash(),
        ]);
    }

    /**
     * @throws \JsonException
     */
    protected function createResponse($data)
    {
        return $this->response = new VerifyEnrolmentResponse($this, $data);
    }

    /**
     * @throws \JsonException
     */
    public function sendData($data)
    {
        return $this->createResponse($data);
    }
}
