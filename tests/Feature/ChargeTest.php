<?php

namespace Omnipay\Tosla\Tests\Feature;

use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Tosla\Message\ChargeRequest;
use Omnipay\Tosla\Message\ChargeResponse;
use Omnipay\Tosla\Models\ChargeRequestModel;
use Omnipay\Tosla\Models\ChargeResponseModel;
use Omnipay\Tosla\Tests\TestCase;

class ChargeTest extends TestCase
{
    /**
     * @throws InvalidRequestException
     * @throws InvalidCreditCardException
     * @throws \JsonException
     */
    public function test_charge_request()
    {
        $options = file_get_contents(__DIR__ . '/../Mock/ChargeRequest.json');

        $options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

        $request = new ChargeRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($options);

        $data = $request->getData();

        $this->assertInstanceOf(ChargeRequestModel::class, $data);

        $this->assertEquals('1000000494', $data->clientId);
        $this->assertEquals('POS_ENT_Test_001', $data->apiUser);
        $this->assertEquals('123456', $data->rnd);
        $this->assertEquals('20260322120000', $data->timeSpan);
        $this->assertEquals('TOS-20260322-000001', $data->orderId);
        $this->assertEquals(1522, $data->amount);
        $this->assertEquals(949, $data->currency);
        $this->assertEquals(0, $data->installmentCount);
        $this->assertEquals('Test odeme', $data->description);
        $this->assertEquals('Mehmet Yilmaz', $data->cardHolderName);
        $this->assertEquals('5456165456165454', $data->cardNo);
        $this->assertEquals('1230', $data->expireDate);
        $this->assertEquals('000', $data->cvv);
        $this->assertNotEmpty($data->hash);
    }

    public function test_charge_request_validation_error()
    {
        $options = file_get_contents(__DIR__ . '/../Mock/ChargeRequest-ValidationError.json');

        $options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

        $request = new ChargeRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($options);

        $this->expectException(InvalidRequestException::class);

        $request->getData();
    }

    public function test_charge_response()
    {
        $httpResponse = $this->getMockHttpResponse('ChargeResponseSuccess.txt');

        $response = new ChargeResponse($this->getMockRequest(), $httpResponse);

        $data = $response->getData();

        $this->assertTrue($response->isSuccessful());

        $this->assertInstanceOf(ChargeResponseModel::class, $data);

        $this->assertEquals(0, $data->Code);
        $this->assertEquals('Success', $data->message);
        $this->assertEquals('TOS-20260322-000001', $data->OrderId);
        $this->assertEquals('00', $data->BankResponseCode);
        $this->assertEquals('Onaylandi', $data->BankResponseMessage);
        $this->assertEquals('123456', $data->AuthCode);
        $this->assertEquals('789012345678', $data->HostReferenceNumber);
        $this->assertEquals('TSL-TRX-00001', $data->TransactionId);
        $this->assertEquals('Mehmet Yilmaz', $data->CardHolderName);
        $this->assertEquals('TSL-TRX-00001', $response->getTransactionId());
    }

    public function test_charge_response_api_error()
    {
        $httpResponse = $this->getMockHttpResponse('ChargeResponseApiError.txt');

        $response = new ChargeResponse($this->getMockRequest(), $httpResponse);

        $data = $response->getData();

        $this->assertFalse($response->isSuccessful());

        $this->assertInstanceOf(ChargeResponseModel::class, $data);

        $this->assertEquals(1, $data->Code);
        $this->assertEquals('Kart bilgileri hatali', $data->message);
        $this->assertEquals('05', $data->BankResponseCode);
        $this->assertEquals('Red', $data->BankResponseMessage);
        $this->assertNull($data->OrderId);
        $this->assertNull($data->TransactionId);
    }
}
