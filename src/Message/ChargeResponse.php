<?php

namespace Omnipay\Tosla\Message;

use Omnipay\Common\Message\RequestInterface;
use Omnipay\Tosla\Models\ChargeResponseModel;

class ChargeResponse extends RemoteAbstractResponse
{
    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);

        $this->response = new ChargeResponseModel((array) $this->response);
    }

    public function getData(): ChargeResponseModel
    {
        return $this->response;
    }

    public function isSuccessful(): bool
    {
        return $this->response->BankResponseCode === '00' && $this->response->Code === 0;
    }

    public function getMessage(): ?string
    {
        return implode('. ', array_merge([$this->response->message], $this->response->ValidationErrors ?? []));
    }

    public function getTransactionId(): ?string
    {
        return $this->response->TransactionId;
    }

    public function getCode(): ?string
    {
        return $this->response->Code;
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
