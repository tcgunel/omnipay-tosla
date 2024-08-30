<?php

namespace Omnipay\Tosla\Models;

class VoidRequestModel extends BaseModel
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

    public ?string $orderId;

    public ?string $echo = null;
}
