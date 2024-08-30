<?php

namespace Omnipay\Tosla\Models;

class ChargeResponseModel extends BaseModel
{
    public ?int $Code = null;
    public ?string $message = null;
    public ?array $ValidationErrors = null;
    public ?string $OrderId = null;
    public ?string $BankResponseCode = null;
    public ?string $BankResponseMessage = null;
    public ?string $AuthCode = null;
    public ?string $HostReferenceNumber = null;
    public ?string $TransactionId = null;
    public ?string $CardHolderName = null;
}
