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
    
    /**
     * update exchange rate of currencies by calling API
     *
     */
    public function updateExchangeRate() 
    {
    	$currencies = $this->getCurrencies();

    	$sources = [];
        // array of all symbols
    	foreach($currencies as $currency) {
            $sources[] = $currency['symbol'];
    	}
        
        // gat rates
    	$FixerExchangeApiService = new Application_Service_FixerExchangeApiService();
        $rates_original = $FixerExchangeApiService->getAllRates($sources);
        
        $rates_for_db = array();
        
        // create array of currencies with symbol is key for getting currency info with symbol 
        $currencies_symbol_key = [];
        foreach($currencies as $currency) {
            $currencies_symbol_key[$currency['symbol']] = $currency;
    	}
        
        foreach($rates_original as $rate_from_symbol => $rs) {
            
            foreach($rs as $to_symbol => $to_rate) {
                
                // if currency is not in our currencies table just ignore and continue
                if(!isset($currencies_symbol_key[$to_symbol])) {
                    continue;
                }
                
                $rates_for_db[] = [
                    'currency_from_id' => $currencies_symbol_key[$rate_from_symbol]['id'],
                    'currency_to_id' => $currencies_symbol_key[$to_symbol]['id'],
                    'rate' => $to_rate,
                    'created_at' => date('Y-m-d H:i:s')
                ];
            }
            
        }
        
        // store rates into db
        $this->exchangeModel->storeRates($rates_for_db);
    }

}