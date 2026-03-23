<?php

namespace Omnipay\Tosla\Tests\Feature;

use Omnipay\Tosla\Message\BinLookupRequest;
use Omnipay\Tosla\Message\CheckoutFormRequest;
use Omnipay\Tosla\Message\HistoryRequest;
use Omnipay\Tosla\Message\PaymentInquiryRequest;
use Omnipay\Tosla\Message\PaymentPageRequest;
use Omnipay\Tosla\Message\RefundRequest;
use Omnipay\Tosla\Message\VerifyEnrolmentRequest;
use Omnipay\Tosla\Message\VoidRequest;
use Omnipay\Tosla\Tests\TestCase;

class GatewayTest extends TestCase
{
    public function test_gateway_name()
    {
        $this->assertEquals('Tosla', $this->gateway->getName());
    }

    public function test_gateway_default_parameters()
    {
        $defaults = $this->gateway->getDefaultParameters();

        $this->assertArrayHasKey('clientIp', $defaults);
        $this->assertEquals('127.0.0.1', $defaults['clientIp']);

        $this->assertArrayHasKey('installment', $defaults);
        $this->assertEquals(0, $defaults['installment']);

        $this->assertArrayHasKey('secure', $defaults);
        $this->assertFalse($defaults['secure']);
    }

    public function test_gateway_verify_enrolment()
    {
        $request = $this->gateway->verifyEnrolment([]);

        $this->assertInstanceOf(VerifyEnrolmentRequest::class, $request);
    }

    public function test_gateway_bin_lookup()
    {
        $request = $this->gateway->binLookup([]);

        $this->assertInstanceOf(BinLookupRequest::class, $request);
    }

    public function test_gateway_payment_inquiry()
    {
        $request = $this->gateway->paymentInquiry([]);

        $this->assertInstanceOf(PaymentInquiryRequest::class, $request);
    }

    public function test_gateway_history()
    {
        $request = $this->gateway->history([]);

        $this->assertInstanceOf(HistoryRequest::class, $request);
    }

    public function test_gateway_void()
    {
        $request = $this->gateway->void([]);

        $this->assertInstanceOf(VoidRequest::class, $request);
    }

    public function test_gateway_refund()
    {
        $request = $this->gateway->refund([]);

        $this->assertInstanceOf(RefundRequest::class, $request);
    }

    public function test_gateway_payment_page()
    {
        $request = $this->gateway->paymentPage([]);

        $this->assertInstanceOf(PaymentPageRequest::class, $request);
    }

    public function test_gateway_checkout_form()
    {
        $request = $this->gateway->checkoutForm([]);

        $this->assertInstanceOf(CheckoutFormRequest::class, $request);
    }
}
