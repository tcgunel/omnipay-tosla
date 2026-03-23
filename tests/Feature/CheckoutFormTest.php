<?php

namespace Omnipay\Tosla\Tests\Feature;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Tosla\Message\CheckoutFormRequest;
use Omnipay\Tosla\Message\CheckoutFormResponse;
use Omnipay\Tosla\Models\EnrolmentRequestModel;
use Omnipay\Tosla\Models\EnrolmentResponseModel;
use Omnipay\Tosla\Tests\TestCase;

class CheckoutFormTest extends TestCase
{
    /**
     * @throws InvalidRequestException
     * @throws \JsonException
     */
    public function test_checkout_form_request()
    {
        $options = file_get_contents(__DIR__ . '/../Mock/CheckoutFormRequest.json');

        $options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

        $request = new CheckoutFormRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($options);

        // CheckoutFormRequest extends EnrolmentRequest and has is_checkout_form_request = true
        // so it skips card validation
        $data = $request->getData();

        $this->assertInstanceOf(EnrolmentRequestModel::class, $data);

        $this->assertEquals('1000000494', $data->clientId);
        $this->assertEquals('POS_ENT_Test_001', $data->apiUser);
        $this->assertEquals('https://example.com/checkout-callback', $data->callbackUrl);
        $this->assertEquals('TOS-20260322-000006', $data->orderId);
        $this->assertEquals(7500, $data->amount);
        $this->assertEquals(949, $data->currency);
        $this->assertEquals(3, $data->installmentCount);
        $this->assertEquals('Checkout form testi', $data->description);
        $this->assertNotEmpty($data->hash);
        $this->assertNotEmpty($data->rnd);
        $this->assertNotEmpty($data->timeSpan);
    }

    public function test_checkout_form_request_validation_error()
    {
        $options = file_get_contents(__DIR__ . '/../Mock/CheckoutFormRequest-ValidationError.json');

        $options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

        $request = new CheckoutFormRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($options);

        $this->expectException(InvalidRequestException::class);

        $request->getData();
    }

    public function test_checkout_form_response()
    {
        $httpResponse = $this->getMockHttpResponse('CheckoutFormResponseSuccess.txt');

        $response = new CheckoutFormResponse($this->getMockRequest(), $httpResponse);

        $data = $response->getData();

        $this->assertTrue($response->isSuccessful());

        $this->assertInstanceOf(EnrolmentResponseModel::class, $data);

        $this->assertEquals(0, $data->Code);
        $this->assertEquals('Basarili', $data->Message);
        $this->assertEquals('sess-cf-mno345pqr678', $data->ThreeDSessionId);
        $this->assertEquals('TSL-TRX-00006', $data->TransactionId);
        $this->assertTrue($response->isRedirect());
        $this->assertEquals('Basarili', $response->getMessage());
    }

    public function test_checkout_form_response_api_error()
    {
        $httpResponse = $this->getMockHttpResponse('CheckoutFormResponseApiError.txt');

        $response = new CheckoutFormResponse($this->getMockRequest(), $httpResponse);

        $data = $response->getData();

        $this->assertFalse($response->isSuccessful());

        $this->assertInstanceOf(EnrolmentResponseModel::class, $data);

        $this->assertEquals(1, $data->Code);
        $this->assertEquals('Gecersiz para birimi', $data->Message);
        $this->assertNull($data->ThreeDSessionId);
        $this->assertNull($data->TransactionId);
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('Gecersiz para birimi', $response->getMessage());
    }
}
