<?php

namespace Omnipay\Tosla\Tests\Feature;

use Omnipay\Tosla\Message\ChargeRequest;
use Omnipay\Tosla\Message\EnrolmentRequest;
use Omnipay\Tosla\Tests\TestCase;

class PurchaseTest extends TestCase
{
	/**
	 * Tests that purchase() routes to ChargeRequest when secure is false (default).
	 */
	public function test_purchase_routes_to_charge_request_when_not_secure()
	{
		$request = $this->gateway->purchase([
			'clientId' => '1000000494',
			'apiUser' => 'POS_ENT_Test_001',
			'apiPass' => 'POS_ENT_Test_001!*!*',
			'testMode' => true,
			'amount' => '15.22',
			'currency' => 'TRY',
			'transactionId' => 'TOS-20260322-000001',
			'card' => [
				'firstName' => 'Mehmet',
				'lastName' => 'Yilmaz',
				'number' => '5456165456165454',
				'expiryMonth' => '12',
				'expiryYear' => '2030',
				'cvv' => '000',
			],
		]);

		$this->assertInstanceOf(ChargeRequest::class, $request);
	}

	/**
	 * Tests that purchase() routes to EnrolmentRequest when secure is true in parameters.
	 */
	public function test_purchase_routes_to_enrolment_request_when_secure_in_params()
	{
		$request = $this->gateway->purchase([
			'clientId' => '1000000494',
			'apiUser' => 'POS_ENT_Test_001',
			'apiPass' => 'POS_ENT_Test_001!*!*',
			'testMode' => true,
			'secure' => true,
			'amount' => '25.50',
			'currency' => 'TRY',
			'transactionId' => 'TOS-20260322-000002',
			'returnUrl' => 'https://example.com/3d-callback',
			'card' => [
				'firstName' => 'Ayse',
				'lastName' => 'Demir',
				'number' => '5456165456165454',
				'expiryMonth' => '06',
				'expiryYear' => '2029',
				'cvv' => '123',
			],
		]);

		$this->assertInstanceOf(EnrolmentRequest::class, $request);
	}

	/**
	 * Tests that purchase() routes to EnrolmentRequest when secure is set on gateway.
	 */
	public function test_purchase_routes_to_enrolment_request_when_secure_on_gateway()
	{
		$this->gateway->setSecure(true);

		$request = $this->gateway->purchase([
			'clientId' => '1000000494',
			'apiUser' => 'POS_ENT_Test_001',
			'apiPass' => 'POS_ENT_Test_001!*!*',
			'testMode' => true,
			'amount' => '25.50',
			'currency' => 'TRY',
			'transactionId' => 'TOS-20260322-000003',
			'returnUrl' => 'https://example.com/3d-callback',
			'card' => [
				'firstName' => 'Ali',
				'lastName' => 'Kaya',
				'number' => '5456165456165454',
				'expiryMonth' => '03',
				'expiryYear' => '2028',
				'cvv' => '456',
			],
		]);

		$this->assertInstanceOf(EnrolmentRequest::class, $request);
	}
}
