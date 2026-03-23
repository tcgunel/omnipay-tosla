<?php

namespace Omnipay\Tosla\Tests\Feature;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Tosla\Message\HistoryRequest;
use Omnipay\Tosla\Message\HistoryResponse;
use Omnipay\Tosla\Models\HistoryRequestModel;
use Omnipay\Tosla\Models\HistoryResponseModel;
use Omnipay\Tosla\Models\TransactionModel;
use Omnipay\Tosla\Tests\TestCase;

class HistoryTest extends TestCase
{
    /**
     * @throws InvalidRequestException
     * @throws \JsonException
     */
    public function test_history_request()
    {
        $options = file_get_contents(__DIR__ . '/../Mock/HistoryRequest.json');

        $options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

        // transactionDate needs to be a DateTime object
        $options['transactionDate'] = new \DateTime($options['transactionDate']);

        $request = new HistoryRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($options);

        $data = $request->getData();

        $this->assertInstanceOf(HistoryRequestModel::class, $data);

        $this->assertEquals('1000000494', $data->clientId);
        $this->assertEquals('POS_ENT_Test_001', $data->apiUser);
        $this->assertEquals('20260322', $data->transactionDate);
        $this->assertEquals(1, $data->page);
        $this->assertEquals(10, $data->pageSize);
        $this->assertEquals('TOS-20260322-000001', $data->orderId);
        $this->assertNotEmpty($data->rnd);
        $this->assertNotEmpty($data->timeSpan);
        $this->assertNotEmpty($data->hash);
    }

    public function test_history_request_validation_error()
    {
        $options = file_get_contents(__DIR__ . '/../Mock/HistoryRequest-ValidationError.json');

        $options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

        $request = new HistoryRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($options);

        $this->expectException(InvalidRequestException::class);

        $request->getData();
    }

    public function test_history_response()
    {
        $httpResponse = $this->getMockHttpResponse('HistoryResponseSuccess.txt');

        $response = new HistoryResponse($this->getMockRequest(), $httpResponse);

        $data = $response->getData();

        $this->assertTrue($response->isSuccessful());

        $this->assertInstanceOf(HistoryResponseModel::class, $data);

        $this->assertEquals(0, $data->Code);
        $this->assertEquals('Basarili', $data->Message);
        $this->assertEquals(1, $data->Count);
        $this->assertNotNull($data->Transactions);
        $this->assertCount(1, $data->Transactions);

        /** @var TransactionModel $transaction */
        $transaction = $data->Transactions[0];
        $this->assertInstanceOf(TransactionModel::class, $transaction);
        $this->assertEquals(1, $transaction->TransactionType);
        $this->assertEquals('TOS-20260322-000001', $transaction->OrderId);
        $this->assertEquals('00', $transaction->BankResponseCode);
        $this->assertEquals('Onaylandi', $transaction->BankResponseMessage);
        $this->assertEquals('123456', $transaction->AuthCode);
        $this->assertEquals(1522, $transaction->Amount);
        $this->assertEquals(949, $transaction->Currency);
        $this->assertEquals('TSL-TRX-00001', $transaction->TransactionId);
        $this->assertEquals('Basarili', $response->getMessage());
    }

    public function test_history_response_api_error()
    {
        $httpResponse = $this->getMockHttpResponse('HistoryResponseApiError.txt');

        $response = new HistoryResponse($this->getMockRequest(), $httpResponse);

        $data = $response->getData();

        $this->assertFalse($response->isSuccessful());

        $this->assertInstanceOf(HistoryResponseModel::class, $data);

        $this->assertEquals(1, $data->Code);
        $this->assertEquals('Gecersiz tarih formati', $data->Message);
        $this->assertNull($data->Count);
        $this->assertNull($data->Transactions);
        $this->assertEquals('Gecersiz tarih formati', $response->getMessage());
    }
}
