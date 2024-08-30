<?php

require_once '../vendor/autoload.php';

/** @var Omnipay\Tosla\Gateway $gateway */
$gateway = \Omnipay\Omnipay::create('Tosla');

$gateway
    ->setClientId('1000000494')
    ->setApiUser('POS_ENT_Test_001')
    ->setApiPass('POS_ENT_Test_001!*!*')
    ->setTestMode(true);

\Omnipay\Tosla\Helpers\Helper::prettyPrint($_REQUEST);
$options = [
    'orderId' => $_REQUEST['OrderId'],
    'mdStatus' => $_REQUEST['MdStatus'],
    'threeDSessionId' => $_REQUEST['ThreeDSessionId'],
    'bankResponseCode' => $_REQUEST['BankResponseCode'],
    'bankResponseMessage' => $_REQUEST['BankResponseMessage'],
    'requestStatus' => $_REQUEST['RequestStatus'],
    'hashParameters' => $_REQUEST['HashParameters'],
    'hash' => $_REQUEST['Hash'],
];

/** @var \Omnipay\Tosla\Message\VerifyEnrolmentResponse $response */
$response = $gateway->verifyEnrolment($options)->send();

if ($response->isSuccessful()) {

    $data = $response->getData();

    \Omnipay\Tosla\Helpers\Helper::prettyPrint($data);

} else {

    echo $response->getMessage();

}
