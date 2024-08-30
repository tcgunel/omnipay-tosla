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
//    'orderId' => '66d1b7615e746',
    'transactionDate' => (new DateTime())->setDate(date('Y'), date('n'), date('j')),
    'page' => 1,
    'pageSize' => 5,
];

/** @var \Omnipay\Tosla\Message\HistoryResponse $response */
$response = $gateway->history($options)->send();

if ($response->isSuccessful()) {

    $data = $response->getData();

    \Omnipay\Tosla\Helpers\Helper::prettyPrint($data);

} else {

    echo $response->getMessage();

}
