<?php

namespace Omnipay\Tosla\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Tosla\Traits\PurchaseGettersSetters;

/**
 * Tosla Purchase Request
 */
abstract class RemoteAbstractRequest extends AbstractRequest
{
    use PurchaseGettersSetters;

    protected function get_card($key)
    {
        return $this->getCard() ? $this->getCard()->$key() : null;
    }

    abstract protected function createResponse($data);
}
