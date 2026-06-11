<?php

namespace Omnipay\Tosla\Models;

class InstallmentOptionsRequestModel extends BaseModel
{
    public string $clientId;

    public string $apiUser;

    /**
     * İşlem için üretilmiş random değer. hash içerisinde kullanılan değer ile aynı olmalıdır.
     */
    public string $rnd;

    /**
     * İşlem tarihi (yyyyMMddHHmmss). hash içerisinde kullanılan değer ile aynı olmalıdır.
     * İşlem anında verilen tarih ve saat bilgisi olmalıdır.
     * GTM+3 zaman diliminde ve max 1 saat farka izin verilmektedir.
     * Diğer durumlarda hash hatası alınır.
     */
    public string $timeSpan;

    public string $hash;

    /**
     * İşlem tutarı, son iki hane kuruştur. 1522 = 15 TL 22 Kuruş
     */
    public int $amount;
}
