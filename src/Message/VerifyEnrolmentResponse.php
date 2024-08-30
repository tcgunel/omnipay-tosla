<?php

namespace Omnipay\Tosla\Message;

use Omnipay\Common\Message\RequestInterface;
use Omnipay\Tosla\Models\VerifyEnrolmentRequestModel;

class VerifyEnrolmentResponse extends RemoteAbstractResponse
{
    public function __construct(RequestInterface $request, VerifyEnrolmentRequestModel $data)
    {
        parent::__construct($request, $data);

        $this->response = $data;
    }

    public function getData(): VerifyEnrolmentRequestModel
    {
        return $this->response;
    }

    public function isSuccessful(): bool
    {
        return $this->response->BankResponseCode === '00' && $this->validateHash();
    }

    private function validateHash()
    {
        $hash_parameters = explode(',', $this->response->HashParameters);

        $hash_string = $this->request->getApiPass();
        foreach ($hash_parameters as $hash_parameter) {
            $hash_string .= $this->response->$hash_parameter;
        }

        $hashing_bytes = hash('sha512', ($hash_string), true);

        return $this->response->Hash === base64_encode($hashing_bytes);
    }

    public function getMessage(): ?string
    {
        return $this->response->BankResponseMessage;
    }

    public function getTransactionId(): ?string
    {
        return $this->response->OrderId;
    }

    public function getCode(): ?string
    {
        return $this->response->MdStatus;
    }

    public function getRedirectData()
    {
        return null;
    }

    public function getRedirectUrl(): string
    {
        return '';
    }
}
