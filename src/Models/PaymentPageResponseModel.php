<?php

namespace Omnipay\Tosla\Models;

class PaymentPageResponseModel extends BaseModel
{
    public ?int $Code = null;
    public ?string $Message = null;
    public ?string $ThreeDSessionId = null;
    public ?string $TransactionId = null;
}
