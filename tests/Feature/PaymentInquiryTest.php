<?php

namespace Omnipay\Tosla\Tests\Feature;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Tosla\Message\PaymentInquiryRequest;
use Omnipay\Tosla\Message\PaymentInquiryResponse;
use Omnipay\Tosla\Models\PaymentInquiryRequestModel;
use Omnipay\Tosla\Models\PaymentInquiryResponseModel;
use Omnipay\Tosla\Tests\TestCase;

class PaymentInquiryTest extends TestCase
{
	/**
	 * @throws InvalidRequestException
	 * @throws \JsonException
	 */
	public function test_payment_inquiry_request()
	{
		$options = file_get_contents(__DIR__ . "/../Mock/PaymentInquiryRequest.json");

		$options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

		$request = new PaymentInquiryRequest($this->getHttpClient(), $this->getHttpRequest());

		$request->initialize($options);

		$data = $request->getData();

		$this->assertInstanceOf(PaymentInquiryRequestModel::class, $data);

		$this->assertEquals('1000000494', $data->clientId);
		$this->assertEquals('POS_ENT_Test_001', $data->apiUser);
		$this->assertEquals('TOS-20260322-000001', $data->orderId);
		$this->assertEquals('TSL-TRX-00001', $data->transactionId);
		$this->assertNotEmpty($data->rnd);
		$this->assertNotEmpty($data->timeSpan);
		$this->assertNotEmpty($data->hash);
	}

	public function test_payment_inquiry_request_validation_error()
	{
		$options = file_get_contents(__DIR__ . "/../Mock/PaymentInquiryRequest-ValidationError.json");

		$options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

		$request = new PaymentInquiryRequest($this->getHttpClient(), $this->getHttpRequest());

		$request->initialize($options);

		$this->expectException(InvalidRequestException::class);

		$request->getData();
	}

	public function test_payment_inquiry_response()
	{
		$httpResponse = $this->getMockHttpResponse('PaymentInquiryResponseSuccess.txt');

		$response = new PaymentInquiryResponse($this->getMockRequest(), $httpResponse);

		$data = $response->getData();

		$this->assertTrue($response->isSuccessful());

		$this->assertInstanceOf(PaymentInquiryResponseModel::class, $data);

		$this->assertEquals(0, $data->Code);
		$this->assertEquals('Basarili', $data->Message);
		$this->assertEquals(1, $data->TransactionType);
		$this->assertEquals('2026-03-22T12:00:00', $data->CreateDate);
		$this->assertEquals('TOS-20260322-000001', $data->OrderId);
		$this->assertEquals('00', $data->BankResponseCode);
		$this->assertEquals('Onaylandi', $data->BankResponseMessage);
		$this->assertEquals('123456', $data->AuthCode);
		$this->assertEquals('789012345678', $data->HostReferenceNumber);
		$this->assertEquals(1522, $data->Amount);
		$this->assertEquals(949, $data->Currency);
		$this->assertEquals(0, $data->InstallmentCount);
		$this->assertEquals(1000000494, $data->ClientId);
		$this->assertEquals('545616******5454', $data->CardNo);
		$this->assertEquals(1, $data->RequestStatus);
		$this->assertEquals('TSL-TRX-00001', $data->TransactionId);
		$this->assertEquals('Basarili', $response->getMessage());
	}

	public function test_payment_inquiry_response_api_error()
	{
		$httpResponse = $this->getMockHttpResponse('PaymentInquiryResponseApiError.txt');

		$response = new PaymentInquiryResponse($this->getMockRequest(), $httpResponse);

		$data = $response->getData();

		$this->assertFalse($response->isSuccessful());

		$this->assertInstanceOf(PaymentInquiryResponseModel::class, $data);

		$this->assertEquals(1, $data->Code);
		$this->assertEquals('Islem bulunamadi', $data->Message);
		$this->assertNull($data->OrderId);
		$this->assertNull($data->TransactionId);
		$this->assertNull($data->Amount);
		$this->assertEquals('Islem bulunamadi', $response->getMessage());
	}
}
