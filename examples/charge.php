<?php


ini_set('html_errors', 'On');
require_once '../vendor/autoload.php';

/** @var Omnipay\Tosla\Gateway $gateway */
$gateway = \Omnipay\Omnipay::create('Tosla');

$gateway
    ->setClientId('1000000494')
    ->setApiUser('POS_ENT_Test_001')
    ->setApiPass('POS_ENT_Test_001!*!*')
    ->setTestMode(true);

$options = [
    'transactionId' => uniqid(),
    'amount'        => 600,
    'currency'        => 'TRY',
    'card'          => [  // You can supply \Omnipay\Common\CreditCard object here.
        'firstName'        => 'Example',
        'lastName'         => 'User',
        'number'           => '4159560047417732',
        'expiryMonth'      => '08',
        'expiryYear'       => '2024',
        'cvv'              => '123',
        'billingAddress1'  => '123 Billing St',
        'billingAddress2'  => 'Billsville',
        'billingCity'      => 'Billstown',
        'billingPostcode'  => '12345',
        'billingState'     => 'CA',
        'billingCountry'   => 'TR',
        'billingPhone'     => '5554443322',
        'shippingAddress1' => '123 Shipping St',
        'shippingAddress2' => 'Shipsville',
        'shippingCity'     => 'Shipstown',
        'shippingPostcode' => '54321',
        'shippingState'    => 'NY',
        'shippingCountry'  => 'TR',
        'shippingPhone'    => '5554443322',
    ],
    'clientIp'      => '127.0.0.1',
    'items'         => [ // You can supply \Omnipay\Common\ItemBag here.
        [
            'name'        => 'Perspiciatis et facilis tempore facilis.',
            'description' => 'My notion was that she was talking. \'How CAN I have done that?\' she thought. \'I must be a LITTLE larger, sir, if you like,\' said the King and Queen of Hearts, carrying the King\'s crown on a.',
            'quantity'    => 6,
            'price'       => 100,
        ],
    ],
    'secure'        => false,
];

/** @var \Omnipay\Tosla\Message\ChargeResponse $response */
$response = $gateway->purchase($options)->send();

if ($response->isSuccessful()) {

    /** @var \Omnipay\Tosla\Models\ChargeResponseModel $data */
    $data = $response->getData();

    \Omnipay\Tosla\Helpers\Helper::prettyPrint($data);

} else {

    echo $response->getMessage();

}
