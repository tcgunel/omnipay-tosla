<?php

require_once '../vendor/autoload.php';

/** @var Omnipay\Tosla\Gateway $gateway */
$gateway = \Omnipay\Omnipay::create('Tosla');

$gateway
    ->setClientId('1000000494')
    ->setApiUser('POS_ENT_Test_001')
    ->setApiPass('POS_ENT_Test_001!*!*')
    ->setTestMode(true);

$options = [
    'orderId' => '66d01ca0385ef',
];

/** @var \Omnipay\Tosla\Message\PaymentInquiryResponse $response */
$response = $gateway->paymentInquiry($options)->send();

if ($response->isSuccessful()) {

    $data = $response->getData();

    \Omnipay\Tosla\Helpers\Helper::prettyPrint($data);

} else {

    echo $response->getMessage();

}
