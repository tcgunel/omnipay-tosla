<?php

require_once '../vendor/autoload.php';

/** @var Omnipay\Tosla\Gateway $gateway */
$gateway = \Omnipay\Omnipay::create('Tosla');

$gateway
    ->setClientId('1000000494')
    ->setApiUser('POS_ENT_Test_001')
    ->setApiPass('POS_ENT_Test_001!*!*')
    ->setTestMode(true);

// Amount is given in major units (TL); the package converts it to kuruş.
$options = [
    'amount' => '100.00',
];

/** @var \Omnipay\Tosla\Message\InstallmentOptionsResponse $response */
$response = $gateway->installmentOptions($options)->send();

if ($response->isSuccessful()) {

    foreach ($response->getData()->InstallmentOptions as $option) {
        // $option->Amount is the totalAmount to send with isCommission = 1
        // for the matching $option->Installment count.
        echo sprintf(
            "%d taksit (%s): %.2f TL\n",
            $option->Installment,
            $option->Title,
            $option->Amount / 100
        );
    }

} else {

    echo $response->getMessage();

}
