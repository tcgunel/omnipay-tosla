<?php

namespace Omnipay\Tosla\Traits;

trait PurchaseGettersSetters
{
    public function getClientId()
    {
        return $this->getParameter('clientId');
    }

    public function setClientId($value)
    {
        return $this->setParameter('clientId', $value);
    }

    public function getApiUser()
    {
        return $this->getParameter('apiUser');
    }

    public function setApiUser($value)
    {
        return $this->setParameter('apiUser', $value);
    }

    public function getApiPass()
    {
        return $this->getParameter('apiPass');
    }

    public function setApiPass($value)
    {
        return $this->setParameter('apiPass', $value);
    }

    public function getHasInstallmentComission()
    {
        return $this->getParameter('hasInstallmentCommission');
    }

    public function setHasInstallmentComission($value)
    {
        return $this->setParameter('hasInstallmentCommission', $value);
    }

    public function getSecure()
    {
        return $this->getParameter('secure');
    }

    public function setSecure($value)
    {
        return $this->setParameter('secure', $value);
    }

    public function getEndpoint()
    {
        return ($this->getTestMode() ? 'https://prepentegrasyon.tosla.com/api/Payment' : 'https://entegrasyon.tosla.com/api/Payment').$this->endpoint;
    }

    public function getClientIp()
    {
        return $this->getParameter('clientIp');
    }

    public function setClientIp($value)
    {
        return $this->setParameter('clientIp', $value);
    }

    public function getInstallment()
    {
        return $this->getParameter('installment');
    }

    public function setInstallment($value)
    {
        return $this->setParameter('installment', $value);
    }

    public function getEcho()
    {
        return $this->getParameter('echo');
    }

    public function setEcho($value)
    {
        return $this->setParameter('echo', $value);
    }

    public function getExtraParameters()
    {
        return $this->getParameter('extraParameters');
    }

    public function setExtraParameters($value)
    {
        return $this->setParameter('extraParameters', $value);
    }

    public function getOrderId()
    {
        return $this->getParameter('orderId');
    }

    public function setOrderId($value)
    {
        return $this->setParameter('orderId', $value);
    }

    public function getTransactionDate(): ?\DateTime
    {
        return $this->getParameter('transactionDate');
    }

    public function setTransactionDate(?\DateTime $value)
    {
        return $this->setParameter('transactionDate', $value);
    }

    public function getPage()
    {
        return $this->getParameter('page');
    }

    public function setPage($value)
    {
        return $this->setParameter('page', $value);
    }

    public function getPageSize()
    {
        return $this->getParameter('pageSize');
    }

    public function setPageSize($value)
    {
        return $this->setParameter('pageSize', $value);
    }

    public function getMdStatus()
    {
        return $this->getParameter('mdStatus');
    }

    public function setMdStatus($value)
    {
        return $this->setParameter('mdStatus', $value);
    }

    public function getThreeDSessionId()
    {
        return $this->getParameter('threeDSessionId');
    }

    public function setThreeDSessionId($value)
    {
        return $this->setParameter('threeDSessionId', $value);
    }

    public function getBankResponseCode()
    {
        return $this->getParameter('bankResponseCode');
    }

    public function setBankResponseCode($value)
    {
        return $this->setParameter('bankResponseCode', $value);
    }

    public function getBankResponseMessage()
    {
        return $this->getParameter('bankResponseMessage');
    }

    public function setBankResponseMessage($value)
    {
        return $this->setParameter('bankResponseMessage', $value);
    }

    public function getRequestStatus()
    {
        return $this->getParameter('requestStatus');
    }

    public function setRequestStatus($value)
    {
        return $this->setParameter('requestStatus', $value);
    }

    public function getHashParameters()
    {
        return $this->getParameter('hashParameters');
    }

    public function setHashParameters($value)
    {
        return $this->setParameter('hashParameters', $value);
    }

    public function getHash()
    {
        return $this->getParameter('hash');
    }

    public function setHash($value)
    {
        return $this->setParameter('hash', $value);
    }

    public function getRnd()
    {
        return $this->getParameter('rnd');
    }

    public function setRnd($value)
    {
        return $this->setParameter('rnd', $value);
    }

    public function getTimeSpan()
    {
        return $this->getParameter('timeSpan');
    }

    public function setTimeSpan($value)
    {
        return $this->setParameter('timeSpan', $value);
    }
}
