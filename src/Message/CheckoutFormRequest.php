<?php

namespace Omnipay\Tosla\Message;

class CheckoutFormRequest extends EnrolmentRequest
{
    public bool $is_checkout_form_request = true;

    protected function createResponse($data): CheckoutFormResponse
    {
        return $this->response = new CheckoutFormResponse($this, $data);
    }
}
