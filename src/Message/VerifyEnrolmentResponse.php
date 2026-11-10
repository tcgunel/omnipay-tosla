<?php

namespace Omnipay\Tosla\Message;

use Omnipay\Common\Message\RequestInterface;
use Omnipay\Tosla\Models\VerifyEnrolmentRequestModel;

class VerifyEnrolmentResponse extends RemoteAbstractResponse
{
    public function __construct(RequestInterface $request, VerifyEnrolmentRequestModel $data)
    {
        parent::__construct($request, $data);

        $this->response = $data;
    }

    public function getData(): VerifyEnrolmentRequestModel
    {
        return $this->response;
    }

    public function isSuccessful(): bool
    {
        return isset($this->response->BankResponseCode)
            && $this->response->BankResponseCode === '00'
            && $this->validateHash();
    }

    /**
     * Fields that must be covered by the callback hash. HashParameters is
     * posted by the caller, so without this list an attacker could sign an
     * empty or harmless field set and change OrderId or BankResponseCode.
     */
    private const REQUIRED_HASH_PARAMETERS = ['OrderId', 'BankResponseCode'];

    private function validateHash(): bool
    {
        $secret = (string) $this->request->getApiPass();

        $posted_hash = isset($this->response->Hash) ? (string) $this->response->Hash : '';

        $hash_parameters_raw = isset($this->response->HashParameters) ? (string) $this->response->HashParameters : '';

        if ($secret === '' || $posted_hash === '' || trim($hash_parameters_raw) === '') {
            return false;
        }

        $hash_parameters = array_map('trim', explode(',', $hash_parameters_raw));

        foreach (self::REQUIRED_HASH_PARAMETERS as $required) {
            if (! in_array($required, $hash_parameters, true)) {
                return false;
            }

            if (! isset($this->response->$required) || (string) $this->response->$required === '') {
                return false;
            }
        }

        $hash_string = $secret;
        foreach ($hash_parameters as $hash_parameter) {
            $hash_string .= ($hash_parameter !== '' && property_exists($this->response, $hash_parameter) && isset($this->response->$hash_parameter))
                ? (string) $this->response->$hash_parameter
                : '';
        }

        $hashing_bytes = hash('sha512', $hash_string, true);

        return hash_equals(base64_encode($hashing_bytes), $posted_hash);
    }

    public function getMessage(): ?string
    {
        return $this->response->BankResponseMessage;
    }

    public function getTransactionId(): ?string
    {
        return $this->response->OrderId;
    }

    public function getCode(): ?string
    {
        return $this->response->MdStatus;
    }

    public function getRedirectData()
    {
        return null;
    }

    public function getRedirectUrl(): string
    {
        return '';
    }
}
