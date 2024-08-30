<?php

namespace Omnipay\Tosla\Message;

use Omnipay\Common\Message\RequestInterface;
use Omnipay\Tosla\Models\PaymentInquiryResponseModel;

class PaymentInquiryResponse extends RemoteAbstractResponse
{
    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);

        $this->response = new PaymentInquiryResponseModel((array) $this->response);
    }

    public function isSuccessful(): bool
    {
        return $this->response->Code === 0;
    }

    public function getMessage(): string
    {
        return $this->response->Message;
    }

    public function getData(): PaymentInquiryResponseModel
    {
        return $this->response;
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
