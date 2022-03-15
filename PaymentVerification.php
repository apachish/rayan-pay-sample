<?php

class PaymentVerification
{
    public $MerchantID;
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
    public function getMerchantID()
    {
        return $this->MerchantID;
    }

    /**
     * @param mixed $MerchantID
     */
    public function setMerchantID($MerchantID)
    {
        $this->MerchantID = $MerchantID;
    }


}