<?php

namespace Omnipay\Tosla\Models;

class PaymentInquiryResponseModel extends BaseModel
{
    public ?int $Code = null;

    public ?string $Message = null;

    public ?int $TransactionType = null;
    public ?string $CreateDate = null;
    public ?string $OrderId = null;
    public ?string $BankResponseCode = null;
    public ?string $BankResponseMessage = null;
    public ?string $AuthCode = null;
    public ?string $HostReferenceNumber = null;
    public ?int $Amount = null;
    public ?int $Currency = null;
    public ?int $InstallmentCount = null;
    public ?int $ClientId = null;
    public ?string $CardNo = null;
    public ?int $RequestStatus = null;
    public ?int $RefundedAmount = null;
    public ?int $PostAuthedAmount = null;
    public ?string $TransactionId = null;
    public ?int $CommissionStatus = null;
    public ?int $NetAmount = null;
    public ?int $MerchantCommissionAmount = null;
    public ?int $MerchantCommissionRate = null;
    public ?int $CardBankId = null;
    public ?int $CardTypeId = null;
    public ?int $ValorDate = null;
    public ?int $TransactionDate = null;
    public ?int $BankValorDate = null;
    public ?array $ExtraParameters = null;
}
