<?php

namespace Omnipay\Tosla\Tests\Feature;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Tosla\Message\InstallmentOptionsRequest;
use Omnipay\Tosla\Message\InstallmentOptionsResponse;
use Omnipay\Tosla\Models\InstallmentOptionModel;
use Omnipay\Tosla\Models\InstallmentOptionsRequestModel;
use Omnipay\Tosla\Models\InstallmentOptionsResponseModel;
use Omnipay\Tosla\Tests\TestCase;

class InstallmentOptionsTest extends TestCase
{
    /**
     * @throws InvalidRequestException
     */
    public function test_installment_options_request()
    {
        $options = file_get_contents(__DIR__ . '/../Mock/InstallmentOptionsRequest.json');

        $options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

        $request = new InstallmentOptionsRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($options);

        $data = $request->getData();

        $this->assertInstanceOf(InstallmentOptionsRequestModel::class, $data);

        $this->assertEquals('1000000494', $data->clientId);
        $this->assertEquals('POS_ENT_Test_001', $data->apiUser);
        $this->assertNotEmpty($data->rnd);
        $this->assertNotEmpty($data->timeSpan);
        $this->assertNotEmpty($data->hash);
        // 100.00 major units -> 10000 kuruş
        $this->assertEquals(10000, $data->amount);
    }

    public function test_installment_options_request_validation_error()
    {
        $options = file_get_contents(__DIR__ . '/../Mock/InstallmentOptionsRequest-ValidationError.json');

        $options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

        $request = new InstallmentOptionsRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($options);

        $this->expectException(InvalidRequestException::class);

        $request->getData();
    }

    public function test_installment_options_response()
    {
        $httpResponse = $this->getMockHttpResponse('InstallmentOptionsResponseSuccess.txt');

        $response = new InstallmentOptionsResponse($this->getMockRequest(), $httpResponse);

        $data = $response->getData();

        $this->assertTrue($response->isSuccessful());

        $this->assertInstanceOf(InstallmentOptionsResponseModel::class, $data);

        $this->assertEquals(0, $data->Code);
        $this->assertEquals('Basarili', $data->Message);
        $this->assertIsArray($data->InstallmentOptions);
        $this->assertCount(3, $data->InstallmentOptions);

        $this->assertInstanceOf(InstallmentOptionModel::class, $data->InstallmentOptions[0]);
        $this->assertEquals(1, $data->InstallmentOptions[0]->Installment);
        $this->assertEquals('Tek Cekim', $data->InstallmentOptions[0]->Title);
        // Response amount is already in kuruş and must NOT be scaled again.
        $this->assertEquals(10000, $data->InstallmentOptions[0]->Amount);
        $this->assertEquals(949, $data->InstallmentOptions[0]->Currency);

        $this->assertEquals(3, $data->InstallmentOptions[2]->Installment);
        $this->assertEquals(10204, $data->InstallmentOptions[2]->Amount);
    }

    public function test_installment_options_response_api_error()
    {
        $httpResponse = $this->getMockHttpResponse('InstallmentOptionsResponseApiError.txt');

        $response = new InstallmentOptionsResponse($this->getMockRequest(), $httpResponse);

        $data = $response->getData();

        $this->assertFalse($response->isSuccessful());

        $this->assertInstanceOf(InstallmentOptionsResponseModel::class, $data);

        $this->assertEquals(998, $data->Code);
        $this->assertEquals('Validasyon Hatasi', $data->Message);
        $this->assertNull($data->InstallmentOptions);
    }
}
