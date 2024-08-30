<?php

namespace Omnipay\Tosla\Models;

class EnrolmentResponseModel extends BaseModel
{
    public int $Code;
    public string $Message;
    public string $ThreeDSessionId;
    public string $TransactionId;
}
