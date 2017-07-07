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

}