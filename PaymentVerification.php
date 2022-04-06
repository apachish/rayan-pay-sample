<?php

class PaymentVerification
{
    public $MerchantId;
    public $Amount;
    public $Authority;

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->Amount;
    }

    /**
     * @param mixed $Amount
     */
    public function setAmount($Amount)
    {
        $this->Amount = $Amount;
    }

    /**
     * @return mixed
     */
    public function getAuthority()
    {
        return $this->Authority;
    }

    /**
     * @param mixed $Authority
     */
    public function setAuthority($Authority)
    {
        $this->Authority = $Authority;
    }

    /**
     * @return mixed
     */
    public function getMerchantId()
    {
        return $this->MerchantId;
    }

    /**
     * @param mixed $MerchantId
     */
    public function setMerchantID($MerchantId)
    {
        $this->MerchantId = $MerchantId;
    }


}