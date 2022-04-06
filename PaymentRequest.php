<?php

class PaymentRequest
{

        public $Amount ;
        public $CallbackURL ;
        public $Description  ;
        public $Email;
        public $MerchantId ;
        public $Mobile;

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
    public function getCallbackURL()
    {
        return $this->CallbackURL;
    }

    /**
     * @param mixed $CallbackURL
     */
    public function setCallbackURL($CallbackURL)
    {
        $this->CallbackURL = $CallbackURL;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->Description;
    }

    /**
     * @param mixed $Description
     */
    public function setDescription($Description)
    {
        $this->Description = $Description;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->Email;
    }

    /**
     * @param mixed $Email
     */
    public function setEmail($Email)
    {
        $this->Email = $Email;
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
    public function setMerchantId($MerchantId)
    {
        $this->MerchantId = $MerchantId;
    }

    /**
     * @return mixed
     */
    public function getMobile()
    {
        return $this->Mobile;
    }

    /**
     * @param mixed $Mobile
     */
    public function setMobile($Mobile)
    {
        $this->Mobile = $Mobile;
    }
}