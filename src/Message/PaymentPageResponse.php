<?php

namespace Omnipay\Tosla\Message;

use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Tosla\Models\PaymentPageResponseModel;

class PaymentPageResponse extends RemoteAbstractResponse implements RedirectResponseInterface
{
    protected $endpoint = '/threeDSecure/:threeDSessionId';

    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);

        $this->response = new PaymentPageResponseModel((array) $this->response);
    }

    public function getData(): PaymentPageResponseModel
    {
        return $this->response;
    }

    public function isSuccessful(): bool
    {
        return $this->response->Code === 0;
    }

    public function getMessage(): string
    {
        if (! empty($this->response->Message)) {

            return $this->response->Message;

        }

        // Tosla omits Message on some failures (e.g. code 998, bad credentials).
        // Surface the code so the caller still has something to diagnose with.
        return $this->response->Code !== null
            ? sprintf('Tosla error code: %d', $this->response->Code)
            : '';
    }

    public function isRedirect(): bool
    {
        return $this->isSuccessful();
    }

    public function getRedirectData()
    {
        return [
            'threeDSessionId' => $this->getData()->ThreeDSessionId,
        ];
    }

    public function getRedirectMethod()
    {
        return 'GET';
    }

    public function getRedirectUrl()
    {
        $this->request->endpoint = strtr($this->endpoint, [':threeDSessionId' => $this->getData()->ThreeDSessionId]);

        return $this->request->getEndpoint();
    }
}
