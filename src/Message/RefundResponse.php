<?php

namespace Omnipay\Tosla\Message;

use Omnipay\Common\Message\RequestInterface;
use Omnipay\Tosla\Models\RefundResponseModel;

class RefundResponse extends RemoteAbstractResponse
{
    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);

        $this->response = new RefundResponseModel((array) $this->response);
    }

    public function getData(): RefundResponseModel
    {
        return $this->response;
    }

    public function isSuccessful(): bool
    {
        return $this->response->Code === 0;
    }

    public function getMessage(): ?string
    {
        return $this->response->Message;
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
