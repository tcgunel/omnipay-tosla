<?php

namespace Omnipay\Tosla\Models;

class BinLookupRequestModel extends BaseModel
{
    public string $ClientId;

    public string $ApiUser;

    /**
     * İşlem için üretilmiş random değer. hash içerisinde kullanılan değer ile aynı olmalıdır.
     */
    public string $Rnd;

    /**
     * İşlem tarihi (yyyyMMddHHmmss). hash içerisinde kullanılan değer ile aynı olmalıdır.
     * İşlem anında verilen tarih ve saat bilgisi olmalıdır.
     * GTM+3 zaman diliminde ve max 1 saat farka izin verilmektedir.
     * Diğer durumlarda hash hatası alınır.
     */
    public string $TimeSpan;

    public string $Hash;

    public int $Bin;
}
