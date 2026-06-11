<?php

namespace Omnipay\Tosla\Models;

class InstallmentOptionModel extends BaseModel
{
    /**
     * Taksit sayısı. Tek çekim için 1 döner.
     */
    public ?int $Installment = null;

    /**
     * Taksit açıklaması. Örn: "Tek Çekim", "3 Taksit"
     */
    public ?string $Title = null;

    /**
     * Bu taksit seçeneği için toplam tutar, son iki hane kuruştur.
     * isCommission = 1 ile yapılan ödemelerde totalAmount olarak gönderilir.
     */
    public ?int $Amount = null;

    /**
     * İşlem para birimi. TL için 949.
     */
    public ?int $Currency = null;
}
