<?php

namespace Omnipay\Tosla\Tests\Feature;

use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Tosla\Message\EnrolmentRequest;
use Omnipay\Tosla\Message\EnrolmentResponse;
use Omnipay\Tosla\Models\EnrolmentRequestModel;
use Omnipay\Tosla\Models\EnrolmentResponseModel;
use Omnipay\Tosla\Tests\TestCase;

class EnrolmentTest extends TestCase
{
    /**
     * @throws InvalidRequestException
     * @throws InvalidCreditCardException
     * @throws \JsonException
     */
    public function test_enrolment_request()
    {
        $options = file_get_contents(__DIR__ . '/../Mock/EnrolmentRequest.json');

        $options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

        $request = new EnrolmentRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($options);

        $data = $request->getData();

        $this->assertInstanceOf(EnrolmentRequestModel::class, $data);

        $this->assertEquals('1000000494', $data->clientId);
        $this->assertEquals('POS_ENT_Test_001', $data->apiUser);
        $this->assertEquals('https://example.com/3d-callback', $data->callbackUrl);
        $this->assertEquals('TOS-20260322-000002', $data->orderId);
        $this->assertEquals(2550, $data->amount);
        $this->assertEquals(949, $data->currency);
        $this->assertEquals(0, $data->installmentCount);
        $this->assertEquals('3D Secure test odeme', $data->description);
        $this->assertNotEmpty($data->hash);
        $this->assertNotEmpty($data->rnd);
        $this->assertNotEmpty($data->timeSpan);
    }

    public function test_enrolment_request_validation_error()
    {
        $options = file_get_contents(__DIR__ . '/../Mock/EnrolmentRequest-ValidationError.json');

        $options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

        $request = new EnrolmentRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($options);

        $this->expectException(InvalidRequestException::class);

        $request->getData();
    }

    public function test_enrolment_response()
    {
        $httpResponse = $this->getMockHttpResponse('EnrolmentResponseSuccess.txt');

        $response = new EnrolmentResponse($this->getMockRequest(), $httpResponse);

        $data = $response->getData();

        $this->assertTrue($response->isSuccessful());

        $this->assertInstanceOf(EnrolmentResponseModel::class, $data);

        $this->assertEquals(0, $data->Code);
        $this->assertEquals('Basarili', $data->Message);
        $this->assertEquals('sess-3d-abc123def456', $data->ThreeDSessionId);
        $this->assertEquals('TSL-TRX-00002', $data->TransactionId);
        $this->assertTrue($response->isRedirect());
        $this->assertEquals('Basarili', $response->getMessage());
    }

    public function test_enrolment_response_api_error()
    {
        $httpResponse = $this->getMockHttpResponse('EnrolmentResponseApiError.txt');

        $response = new EnrolmentResponse($this->getMockRequest(), $httpResponse);

        $data = $response->getData();

        $this->assertFalse($response->isSuccessful());

        $this->assertInstanceOf(EnrolmentResponseModel::class, $data);

        $this->assertEquals(1, $data->Code);
        $this->assertEquals('Gecersiz istek parametreleri', $data->Message);
        $this->assertNull($data->ThreeDSessionId);
        $this->assertNull($data->TransactionId);
        $this->assertFalse($response->isRedirect());
    }

    /**
     * Tosla returns Message as null on credential failures (code 998).
     * getMessage() must not fatal, and must still expose the code.
     */
    public function test_enrolment_response_auth_error_with_null_message()
    {
        $httpResponse = $this->getMockHttpResponse('EnrolmentResponseAuthError.txt');

        $response = new EnrolmentResponse($this->getMockRequest(), $httpResponse);

        $data = $response->getData();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());

        $this->assertEquals(998, $data->Code);
        $this->assertNull($data->Message);

        $this->assertEquals('Tosla error code: 998', $response->getMessage());
    }

    /**
     * Some Tosla failures omit the Message key entirely. Previously this left
     * the typed property uninitialized and getMessage() threw an Error.
     */
    public function test_enrolment_response_with_missing_message_key()
    {
        $httpResponse = $this->getMockHttpResponse('EnrolmentResponseMessageMissing.txt');

        $response = new EnrolmentResponse($this->getMockRequest(), $httpResponse);

        $data = $response->getData();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());

        $this->assertEquals(998, $data->Code);
        $this->assertNull($data->Message);
        $this->assertNull($data->ThreeDSessionId);
        $this->assertNull($data->TransactionId);

        $this->assertEquals('Tosla error code: 998', $response->getMessage());
    }

    /**
     * An empty body must not fatal either - every property stays null and
     * getMessage() degrades to an empty string.
     */
    public function test_enrolment_response_with_empty_payload()
    {
        $response = new EnrolmentResponse($this->getMockRequest(), []);

        $data = $response->getData();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());

        $this->assertNull($data->Code);
        $this->assertNull($data->Message);

        $this->assertEquals('', $response->getMessage());
    }
}
