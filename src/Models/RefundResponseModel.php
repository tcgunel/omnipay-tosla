<?php

namespace Omnipay\Tosla\Models;

class RefundResponseModel extends BaseModel
{
    public ?int $Code = null;
    public ?string $Message = null;
    public ?string $OrderId = null;
    public ?string $BankResponseCode = null;
    public ?string $BankResponseMessage = null;
    public ?string $AuthCode = null;
    public ?string $HostReferenceNumber = null;
    public ?string $TransactionId = null;
}
