<?php

namespace Omnipay\Tosla\Tests\Feature;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Tosla\Message\RefundRequest;
use Omnipay\Tosla\Message\RefundResponse;
use Omnipay\Tosla\Models\RefundRequestModel;
use Omnipay\Tosla\Models\RefundResponseModel;
use Omnipay\Tosla\Tests\TestCase;

class RefundTest extends TestCase
{
    /**
     * @throws InvalidRequestException
     * @throws \JsonException
     */
    public function test_refund_request()
    {
        $options = file_get_contents(__DIR__ . '/../Mock/RefundRequest.json');

        $options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

        $request = new RefundRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($options);

        $data = $request->getData();

        $this->assertInstanceOf(RefundRequestModel::class, $data);

        $this->assertEquals('1000000494', $data->clientId);
        $this->assertEquals('POS_ENT_Test_001', $data->apiUser);
        $this->assertEquals('TOS-20260322-000001', $data->orderId);
        $this->assertEquals(999, $data->amount);
        $this->assertEquals('refund-test', $data->echo);
        $this->assertNotEmpty($data->rnd);
        $this->assertNotEmpty($data->timeSpan);
        $this->assertNotEmpty($data->hash);
    }

    public function test_refund_request_validation_error()
    {
        $options = file_get_contents(__DIR__ . '/../Mock/RefundRequest-ValidationError.json');

        $options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

        $request = new RefundRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($options);

        $this->expectException(InvalidRequestException::class);

        $request->getData();
    }

    public function test_refund_response()
    {
        $httpResponse = $this->getMockHttpResponse('RefundResponseSuccess.txt');

        $response = new RefundResponse($this->getMockRequest(), $httpResponse);

        $data = $response->getData();

        $this->assertTrue($response->isSuccessful());

        $this->assertInstanceOf(RefundResponseModel::class, $data);

        $this->assertEquals(0, $data->Code);
        $this->assertEquals('Basarili', $data->Message);
        $this->assertEquals('TOS-20260322-000001', $data->OrderId);
        $this->assertEquals('00', $data->BankResponseCode);
        $this->assertEquals('Iade onaylandi', $data->BankResponseMessage);
        $this->assertEquals('111222', $data->AuthCode);
        $this->assertEquals('789012345680', $data->HostReferenceNumber);
        $this->assertEquals('TSL-TRX-00004', $data->TransactionId);
        $this->assertEquals('Basarili', $response->getMessage());
    }

    public function test_refund_response_api_error()
    {
        $httpResponse = $this->getMockHttpResponse('RefundResponseApiError.txt');

        $response = new RefundResponse($this->getMockRequest(), $httpResponse);

        $data = $response->getData();

        $this->assertFalse($response->isSuccessful());

        $this->assertInstanceOf(RefundResponseModel::class, $data);

        $this->assertEquals(1, $data->Code);
        $this->assertEquals('Iade islemi basarisiz', $data->Message);
        $this->assertNull($data->OrderId);
        $this->assertEquals('99', $data->BankResponseCode);
        $this->assertEquals('Yetersiz bakiye', $data->BankResponseMessage);
        $this->assertNull($data->TransactionId);
        $this->assertEquals('Iade islemi basarisiz', $response->getMessage());
    }
}
