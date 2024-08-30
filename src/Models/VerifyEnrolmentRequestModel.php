<?php

namespace Omnipay\Tosla\Models;

class VerifyEnrolmentRequestModel extends BaseModel
{
    public string $ApiUser;
    public string $ClientId;
    public string $OrderId;
    public string $MdStatus;
    public string $ThreeDSessionId;
    public string $BankResponseCode;
    public string $BankResponseMessage;
    public string $RequestStatus;
    public string $HashParameters;
    public string $Hash;
}
