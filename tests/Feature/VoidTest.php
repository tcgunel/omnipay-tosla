<?php

namespace Omnipay\Tosla\Tests\Feature;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Tosla\Message\VoidRequest;
use Omnipay\Tosla\Message\VoidResponse;
use Omnipay\Tosla\Models\VoidRequestModel;
use Omnipay\Tosla\Models\VoidResponseModel;
use Omnipay\Tosla\Tests\TestCase;

class VoidTest extends TestCase
{
	/**
	 * @throws InvalidRequestException
	 * @throws \JsonException
	 */
	public function test_void_request()
	{
		$options = file_get_contents(__DIR__ . "/../Mock/VoidRequest.json");

		$options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

		$request = new VoidRequest($this->getHttpClient(), $this->getHttpRequest());

		$request->initialize($options);

		$data = $request->getData();

		$this->assertInstanceOf(VoidRequestModel::class, $data);

		$this->assertEquals('1000000494', $data->clientId);
		$this->assertEquals('POS_ENT_Test_001', $data->apiUser);
		$this->assertEquals('TOS-20260322-000001', $data->orderId);
		$this->assertEquals('void-test', $data->echo);
		$this->assertNotEmpty($data->rnd);
		$this->assertNotEmpty($data->timeSpan);
		$this->assertNotEmpty($data->hash);
	}

	public function test_void_request_validation_error()
	{
		$options = file_get_contents(__DIR__ . "/../Mock/VoidRequest-ValidationError.json");

		$options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

		$request = new VoidRequest($this->getHttpClient(), $this->getHttpRequest());

		$request->initialize($options);

		$this->expectException(InvalidRequestException::class);

		$request->getData();
	}

	public function test_void_response()
	{
		$httpResponse = $this->getMockHttpResponse('VoidResponseSuccess.txt');

		$response = new VoidResponse($this->getMockRequest(), $httpResponse);

		$data = $response->getData();

		$this->assertTrue($response->isSuccessful());

		$this->assertInstanceOf(VoidResponseModel::class, $data);

		$this->assertEquals(0, $data->Code);
		$this->assertEquals('Basarili', $data->Message);
		$this->assertEquals('TOS-20260322-000001', $data->OrderId);
		$this->assertEquals('00', $data->BankResponseCode);
		$this->assertEquals('Iptal onaylandi', $data->BankResponseMessage);
		$this->assertEquals('654321', $data->AuthCode);
		$this->assertEquals('789012345679', $data->HostReferenceNumber);
		$this->assertEquals('TSL-TRX-00003', $data->TransactionId);
		$this->assertEquals('Basarili', $response->getMessage());
	}

	public function test_void_response_api_error()
	{
		$httpResponse = $this->getMockHttpResponse('VoidResponseApiError.txt');

		$response = new VoidResponse($this->getMockRequest(), $httpResponse);

		$data = $response->getData();

		$this->assertFalse($response->isSuccessful());

		$this->assertInstanceOf(VoidResponseModel::class, $data);

		$this->assertEquals(1, $data->Code);
		$this->assertEquals('Iptal islemi basarisiz', $data->Message);
		$this->assertNull($data->OrderId);
		$this->assertEquals('99', $data->BankResponseCode);
		$this->assertEquals('Islem iptal edilemez', $data->BankResponseMessage);
		$this->assertNull($data->TransactionId);
		$this->assertEquals('Iptal islemi basarisiz', $response->getMessage());
	}
}
