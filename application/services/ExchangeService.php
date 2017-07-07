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
    
    /**
     * save given currency exchange to db & delete old
     * @param integer $from from currency id
     * @param integer $to to currency id
     * @param String $fromAmount
     * @param String $toAmount
     */
    public function cacheResult($from, $to, $fromAmount, $toAmount) 
    {
        // prepare data like columnName => value to save in db
        $data = [
                    'currency_from_id' => $from,
                    'currency_to_id' => $to,
                    'amount_from' => $fromAmount,
                    'amount_to' => $toAmount
                ];
        
        // store data
        $this->exchangeModel->storePreviousexchangereq($data);
               
        

        // delete older cached results by chance of 1/30
        $rand = mt_rand(1,30);
        if($rand == 10) {
            $this->exchangeModel->deleteOldPreviousexchangereq();
        }
    }
    
    /**
     * return latest currency Exchanges that user requested
     * @param integer $number number of rows to return
     * @return array
     */
    public function getPrevExchanges($number)
    {
        return $this->exchangeModel->getPrevExchanges($number);
    }

}