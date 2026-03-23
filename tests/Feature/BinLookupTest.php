<?php

namespace Omnipay\Tosla\Tests\Feature;

use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Tosla\Message\BinLookupRequest;
use Omnipay\Tosla\Message\BinLookupResponse;
use Omnipay\Tosla\Models\BinLookupRequestModel;
use Omnipay\Tosla\Models\BinLookupResponseModel;
use Omnipay\Tosla\Tests\TestCase;

class BinLookupTest extends TestCase
{
	/**
	 * @throws \Omnipay\Common\Exception\InvalidRequestException
	 * @throws InvalidCreditCardException
	 * @throws \JsonException
	 */
	public function test_bin_lookup_request()
	{
		$options = file_get_contents(__DIR__ . "/../Mock/BinLookupRequest.json");

		$options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

		$request = new BinLookupRequest($this->getHttpClient(), $this->getHttpRequest());

		$request->initialize($options);

		$data = $request->getData();

		$this->assertInstanceOf(BinLookupRequestModel::class, $data);

		$this->assertEquals('1000000494', $data->ClientId);
		$this->assertEquals('POS_ENT_Test_001', $data->ApiUser);
		$this->assertNotEmpty($data->Rnd);
		$this->assertNotEmpty($data->TimeSpan);
		$this->assertNotEmpty($data->Hash);
		$this->assertEquals(545616545616, $data->Bin);
	}

	public function test_bin_lookup_request_validation_error()
	{
		$options = file_get_contents(__DIR__ . "/../Mock/BinLookupRequest-ValidationError.json");

		$options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

		$request = new BinLookupRequest($this->getHttpClient(), $this->getHttpRequest());

		$request->initialize($options);

		$this->expectException(InvalidCreditCardException::class);

		$request->getData();
	}

	public function test_bin_lookup_response()
	{
		$httpResponse = $this->getMockHttpResponse('BinLookupResponseSuccess.txt');

		$response = new BinLookupResponse($this->getMockRequest(), $httpResponse);

		$data = $response->getData();

		$this->assertTrue($response->isSuccessful());

		$this->assertInstanceOf(BinLookupResponseModel::class, $data);

		$this->assertEquals(0, $data->Code);
		$this->assertEquals('Basarili', $data->Message);
		$this->assertEquals(545616, $data->CardPrefix);
		$this->assertEquals(111, $data->BankId);
		$this->assertEquals('0111', $data->BankCode);
		$this->assertEquals('QNB Finansbank', $data->BankName);
		$this->assertEquals('CardFinans', $data->CardName);
		$this->assertEquals('credit', $data->CardClass);
		$this->assertEquals('mastercard', $data->CardType);
		$this->assertEquals('TR', $data->Country);
		$this->assertNotNull($data->CommissionPackages);
		$this->assertEquals('Basarili', $response->getMessage());
	}

	public function test_bin_lookup_response_api_error()
	{
		$httpResponse = $this->getMockHttpResponse('BinLookupResponseApiError.txt');

		$response = new BinLookupResponse($this->getMockRequest(), $httpResponse);

		$data = $response->getData();

		$this->assertFalse($response->isSuccessful());

		$this->assertInstanceOf(BinLookupResponseModel::class, $data);

		$this->assertEquals(1, $data->Code);
		$this->assertEquals('Bin bilgisi bulunamadi', $data->Message);
		$this->assertNull($data->CardPrefix);
		$this->assertNull($data->BankId);
		$this->assertNull($data->BankName);
		$this->assertNull($data->CommissionPackages);
	}
}
