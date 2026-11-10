<?php

namespace Omnipay\Tosla\Tests\Feature;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Tosla\Message\VerifyEnrolmentRequest;
use Omnipay\Tosla\Message\VerifyEnrolmentResponse;
use Omnipay\Tosla\Models\VerifyEnrolmentRequestModel;
use Omnipay\Tosla\Tests\TestCase;

class VerifyEnrolmentTest extends TestCase
{
    /**
     * @throws InvalidRequestException
     * @throws \JsonException
     */
    public function test_verify_enrolment_request()
    {
        $options = file_get_contents(__DIR__ . '/../Mock/VerifyEnrolmentRequest.json');

        $options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

        $request = new VerifyEnrolmentRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($options);

        $data = $request->getData();

        $this->assertInstanceOf(VerifyEnrolmentRequestModel::class, $data);

        $this->assertEquals('POS_ENT_Test_001', $data->ApiUser);
        $this->assertEquals('1000000494', $data->ClientId);
        $this->assertEquals('TOS-20260322-000002', $data->OrderId);
        $this->assertEquals('1', $data->MdStatus);
        $this->assertEquals('sess-3d-abc123def456', $data->ThreeDSessionId);
        $this->assertEquals('00', $data->BankResponseCode);
        $this->assertEquals('Onaylandi', $data->BankResponseMessage);
        $this->assertEquals('1', $data->RequestStatus);
        $this->assertNotEmpty($data->HashParameters);
        $this->assertNotEmpty($data->Hash);
    }

    public function test_verify_enrolment_request_validation_error()
    {
        $options = file_get_contents(__DIR__ . '/../Mock/VerifyEnrolmentRequest-ValidationError.json');

        $options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

        $request = new VerifyEnrolmentRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($options);

        $this->expectException(InvalidRequestException::class);

        $request->getData();
    }

    public function test_verify_enrolment_response()
    {
        $options = file_get_contents(__DIR__ . '/../Mock/VerifyEnrolmentRequest.json');

        $options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

        $request = new VerifyEnrolmentRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($options);

        // VerifyEnrolment sendData passes $data directly to the response (no HTTP call)
        $data = $request->getData();

        $response = new VerifyEnrolmentResponse($request, $data);

        $this->assertInstanceOf(VerifyEnrolmentRequestModel::class, $response->getData());

        $this->assertEquals('TOS-20260322-000002', $response->getTransactionId());
        $this->assertEquals('1', $response->getCode());
        $this->assertEquals('Onaylandi', $response->getMessage());
    }

    public function test_verify_enrolment_response_failed_bank_response()
    {
        $failedData = new VerifyEnrolmentRequestModel([
            'ApiUser' => 'POS_ENT_Test_001',
            'ClientId' => '1000000494',
            'OrderId' => 'TOS-20260322-000002',
            'MdStatus' => '0',
            'ThreeDSessionId' => 'sess-3d-abc123def456',
            'BankResponseCode' => '05',
            'BankResponseMessage' => 'Red - Kart sahibi veya bankasi tarafindan onaylanmadi',
            'RequestStatus' => '0',
            'HashParameters' => 'ClientId,ApiUser',
            'Hash' => 'invalid-hash',
        ]);

        $request = new VerifyEnrolmentRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize([
            'clientId' => '1000000494',
            'apiUser' => 'POS_ENT_Test_001',
            'apiPass' => 'POS_ENT_Test_001!*!*',
            'orderId' => 'TOS-20260322-000002',
            'mdStatus' => '0',
            'threeDSessionId' => 'sess-3d-abc123def456',
            'bankResponseCode' => '05',
            'bankResponseMessage' => 'Red - Kart sahibi veya bankasi tarafindan onaylanmadi',
            'requestStatus' => '0',
            'hashParameters' => 'ClientId,ApiUser',
            'hash' => 'invalid-hash',
        ]);

        $response = new VerifyEnrolmentResponse($request, $failedData);

        $this->assertFalse($response->isSuccessful());

        $this->assertEquals('Red - Kart sahibi veya bankasi tarafindan onaylanmadi', $response->getMessage());
        $this->assertEquals('0', $response->getCode());
    }

    private const API_PASS = 'POS_ENT_Test_001!*!*';

    private function callbackFields(): array
    {
        return [
            'ApiUser' => 'POS_ENT_Test_001',
            'ClientId' => '1000000494',
            'OrderId' => 'TOS-20260322-000002',
            'MdStatus' => '1',
            'ThreeDSessionId' => 'sess-3d-abc123def456',
            'BankResponseCode' => '00',
            'BankResponseMessage' => 'Onaylandi',
            'RequestStatus' => '1',
        ];
    }

    private function sign(array $fields, string $hashParameters, string $secret = self::API_PASS): string
    {
        $str = $secret;
        foreach (explode(',', $hashParameters) as $name) {
            $str .= $fields[$name] ?? '';
        }

        return base64_encode(hash('sha512', $str, true));
    }

    private function makeResponse(array $fields, string $apiPass = self::API_PASS): VerifyEnrolmentResponse
    {
        $request = new VerifyEnrolmentRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize(['apiPass' => $apiPass]);

        return new VerifyEnrolmentResponse($request, new VerifyEnrolmentRequestModel($fields));
    }

    public function test_valid_hash_covering_required_fields_is_successful()
    {
        $fields = $this->callbackFields();
        $fields['HashParameters'] = 'ClientId,ApiUser,OrderId,MdStatus,BankResponseCode,BankResponseMessage,RequestStatus';
        $fields['Hash'] = $this->sign($fields, $fields['HashParameters']);

        $this->assertTrue($this->makeResponse($fields)->isSuccessful());
    }

    public function test_valid_hash_without_order_id_in_hash_parameters_is_rejected()
    {
        $fields = $this->callbackFields();
        $fields['HashParameters'] = 'ClientId,ApiUser,MdStatus,BankResponseCode,RequestStatus';
        $fields['Hash'] = $this->sign($fields, $fields['HashParameters']);

        $this->assertFalse($this->makeResponse($fields)->isSuccessful());
    }

    public function test_valid_hash_without_bank_response_code_in_hash_parameters_is_rejected()
    {
        $fields = $this->callbackFields();
        $fields['HashParameters'] = 'ClientId,ApiUser,OrderId,MdStatus';
        $fields['Hash'] = $this->sign($fields, $fields['HashParameters']);

        $this->assertFalse($this->makeResponse($fields)->isSuccessful());
    }

    public function test_missing_hash_is_rejected()
    {
        $fields = $this->callbackFields();
        $fields['HashParameters'] = 'ClientId,ApiUser,OrderId,BankResponseCode';

        $this->assertFalse($this->makeResponse($fields)->isSuccessful());

        $fields['Hash'] = '';
        $this->assertFalse($this->makeResponse($fields)->isSuccessful());
    }

    public function test_missing_hash_parameters_is_rejected()
    {
        $fields = $this->callbackFields();
        $fields['Hash'] = $this->sign($fields, '');

        $this->assertFalse($this->makeResponse($fields)->isSuccessful());
    }

    public function test_empty_api_secret_is_rejected()
    {
        $fields = $this->callbackFields();
        $fields['HashParameters'] = 'ClientId,ApiUser,OrderId,BankResponseCode';
        $fields['Hash'] = $this->sign($fields, $fields['HashParameters'], '');

        $this->assertFalse($this->makeResponse($fields, '')->isSuccessful());
    }

    public function test_tampered_field_is_rejected()
    {
        $fields = $this->callbackFields();
        $fields['HashParameters'] = 'ClientId,ApiUser,OrderId,MdStatus,BankResponseCode,BankResponseMessage,RequestStatus';
        $fields['Hash'] = $this->sign($fields, $fields['HashParameters']);
        $fields['OrderId'] = 'TOS-20260322-999999';

        $this->assertFalse($this->makeResponse($fields)->isSuccessful());
    }
}
