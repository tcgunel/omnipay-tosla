<?php

namespace Omnipay\Tosla;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Tosla\Message\BinLookupRequest;
use Omnipay\Tosla\Message\ChargeRequest;
use Omnipay\Tosla\Message\CompletePurchaseRequest;
use Omnipay\Tosla\Message\EnrolmentRequest;
use Omnipay\Tosla\Message\HistoryRequest;
use Omnipay\Tosla\Message\PaymentInquiryRequest;
use Omnipay\Tosla\Message\PaymentPageRequest;
use Omnipay\Tosla\Message\RefundRequest;
use Omnipay\Tosla\Message\VerifyEnrolmentRequest;
use Omnipay\Tosla\Message\VoidRequest;
use Omnipay\Tosla\Traits\PurchaseGettersSetters;

/**
 * Tosla Gateway
 * (c) Tolga Can Günel
 * 2015, mobius.studio
 * http://www.github.com/tcgunel/omnipay-tosla
 *
 * @method \Omnipay\Common\Message\NotificationInterface acceptNotification(array $options = [])
 * @method \Omnipay\Common\Message\RequestInterface completeAuthorize(array $options = [])
 */
class Gateway extends AbstractGateway
{
    use PurchaseGettersSetters;

    public function getName(): string
    {
        return 'Tosla';
    }

    public function getDefaultParameters()
    {
        return [
            'clientIp' => '127.0.0.1',

            'installment' => 0,
            'secure' => false,
        ];
    }

    public function purchase(array $parameters = [])
    {
        if (
            (array_key_exists('secure', $parameters) && $parameters['secure'] === true) ||
            $this->getSecure() === true
        ) {

            return $this->createRequest(EnrolmentRequest::class, $parameters);

        }

        return $this->createRequest(ChargeRequest::class, $parameters);
    }

    public function verifyEnrolment(array $parameters = []): AbstractRequest
    {
        return $this->createRequest(VerifyEnrolmentRequest::class, $parameters);
    }

    public function binLookup(array $parameters = []): AbstractRequest
    {
        return $this->createRequest(BinLookupRequest::class, $parameters);
    }

    public function paymentInquiry(array $parameters = []): AbstractRequest
    {
        return $this->createRequest(PaymentInquiryRequest::class, $parameters);
    }

    public function history(array $parameters = []): AbstractRequest
    {
        return $this->createRequest(HistoryRequest::class, $parameters);
    }

    public function void(array $parameters = []): AbstractRequest
    {
        return $this->createRequest(VoidRequest::class, $parameters);
    }

    public function refund(array $parameters = []): AbstractRequest
    {
        return $this->createRequest(RefundRequest::class, $parameters);
    }

    public function paymentPage(array $parameters = []): AbstractRequest
    {
        return $this->createRequest(PaymentPageRequest::class, $parameters);
    }
}
