<?php

class Application_Service_ExchangeService
{
    
    private $exchangeModel;
    public function __construct()
    {
        $this->exchangeModel = new Application_Model_DbTable_Exchanges();        
    }

    /**
     * get all currencies in 'currencies' table
     * @return array
     */
    public function getCurrencies()
    {
        return $this->exchangeModel->getCurrencies();
    }
    
    /**
     * return exchange amount based on from and to currencies and from amount
     * @param integer $from
     * @param integer $to
     * @param string $amount
     * @return float
     */
    public function getExchangeInfo($from, $to, $amount) 
    {
        // if $to and $from are equal $rate is 1
        if($from === $to) {
            $rate = 1;
        } else {
            $rate = $this->exchangeModel->getLatestExchangeRate($from, $to);
        }
        
        return $amount * $rate;
    }

}