<?php

class IndexController extends Zend_Controller_Action
{

    private $exchangeService; // do all currency converting with this class object
    public function init()
    {
        $this->exchangeService = new Application_Service_ExchangeService();
    }

    public function indexAction()
    {
        $currencies = $this->exchangeService->getCurrencies();
        $this->view->currencies = $currencies;
    }


}

