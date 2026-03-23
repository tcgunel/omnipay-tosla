<?php

namespace Omnipay\Tosla\Tests\Feature;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Tosla\Message\PaymentPageRequest;
use Omnipay\Tosla\Message\PaymentPageResponse;
use Omnipay\Tosla\Models\PaymentPageRequestModel;
use Omnipay\Tosla\Models\PaymentPageResponseModel;
use Omnipay\Tosla\Tests\TestCase;

class PaymentPageTest extends TestCase
{
	/**
	 * @throws InvalidRequestException
	 * @throws \JsonException
	 */
	public function test_payment_page_request()
	{
		$options = file_get_contents(__DIR__ . "/../Mock/PaymentPageRequest.json");

		$options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

		$request = new PaymentPageRequest($this->getHttpClient(), $this->getHttpRequest());

		$request->initialize($options);

		$data = $request->getData();

		$this->assertInstanceOf(PaymentPageRequestModel::class, $data);

		$this->assertEquals('1000000494', $data->clientId);
		$this->assertEquals('POS_ENT_Test_001', $data->apiUser);
		$this->assertEquals('https://example.com/payment-page-callback', $data->callbackUrl);
		$this->assertEquals('TOS-20260322-000005', $data->orderId);
		$this->assertEquals('49.90', $data->amount);
		$this->assertEquals(949, $data->currency);
		$this->assertEquals(0, $data->installmentCount);
		$this->assertEquals('Ortak odeme sayfasi testi', $data->description);
		$this->assertNotEmpty($data->hash);
		$this->assertNotEmpty($data->rnd);
		$this->assertNotEmpty($data->timeSpan);
	}

	public function test_payment_page_request_validation_error()
	{
		$options = file_get_contents(__DIR__ . "/../Mock/PaymentPageRequest-ValidationError.json");

		$options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

		$request = new PaymentPageRequest($this->getHttpClient(), $this->getHttpRequest());

		$request->initialize($options);

		$this->expectException(InvalidRequestException::class);

		$request->getData();
	}

	public function test_payment_page_response()
	{
		$httpResponse = $this->getMockHttpResponse('PaymentPageResponseSuccess.txt');

		$response = new PaymentPageResponse($this->getMockRequest(), $httpResponse);

		$data = $response->getData();

		$this->assertTrue($response->isSuccessful());

		$this->assertInstanceOf(PaymentPageResponseModel::class, $data);

		$this->assertEquals(0, $data->Code);
		$this->assertEquals('Basarili', $data->Message);
		$this->assertEquals('sess-pp-xyz789ghi012', $data->ThreeDSessionId);
		$this->assertEquals('TSL-TRX-00005', $data->TransactionId);
		$this->assertTrue($response->isRedirect());
		$this->assertEquals('GET', $response->getRedirectMethod());
		$this->assertEquals('Basarili', $response->getMessage());

		$redirectData = $response->getRedirectData();
		$this->assertEquals('sess-pp-xyz789ghi012', $redirectData['threeDSessionId']);
	}

	public function test_payment_page_response_api_error()
	{
		$httpResponse = $this->getMockHttpResponse('PaymentPageResponseApiError.txt');

		$response = new PaymentPageResponse($this->getMockRequest(), $httpResponse);

		$data = $response->getData();

		$this->assertFalse($response->isSuccessful());

		$this->assertInstanceOf(PaymentPageResponseModel::class, $data);

		$this->assertEquals(1, $data->Code);
		$this->assertEquals('Gecersiz tutar', $data->Message);
		$this->assertFalse($response->isRedirect());
		$this->assertEquals('Gecersiz tutar', $response->getMessage());
	}
}
