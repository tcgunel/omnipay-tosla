<?php

namespace Omnipay\Tosla\Message;

use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Tosla\Models\EnrolmentResponseModel;
use Omnipay\Tosla\Traits\PurchaseGettersSetters;

class CheckoutFormResponse extends RemoteAbstractResponse implements RedirectResponseInterface
{
    use PurchaseGettersSetters;

	public function __construct(RequestInterface $request, $data)
	{
		parent::__construct($request, $data);

		$this->response = new EnrolmentResponseModel((array)$this->response);
	}

	public function getData(): EnrolmentResponseModel
	{
		return $this->response;
	}

	public function isSuccessful(): bool
	{
        return $this->response->Code === 0;
	}

	public function getMessage(): string
	{
		return $this->response->Message;
	}

	public function isRedirect(): bool
	{
        return $this->isSuccessful();
	}

	public function getRedirectUrl(): ?string
	{
        $endpoint = str_replace('threeDPayment', 'threeDSecure', $this->getRequest()->getEndpoint());

		return sprintf('%s/%s', $endpoint, $this->response->ThreeDSessionId);
	}
}
