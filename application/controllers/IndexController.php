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

    public function getexchangeinfoAction()
    {
        // validate 
        $form = new Application_Form_ExchangeForm();
        if (!$form->isValid($_POST)) {
            // Failed validation; redisplay form
            $formMessageFormatter = new Application_Form_FormMessageFormatter();
            $messages = $formMessageFormatter->getMessagesForAjax($form);
           
            $data = [
                'status' => 'failed',
                'errMsg' => $messages
            ];
        } else {
            // retirieve data from request
            $from = $this->_getParam('from');
            $to = $this->_getParam('to');
            $fromAmount = $this->_getParam('amount');

            $toAmount = $this->exchangeService->getExchangeInfo($from, $to, $fromAmount);

            $data = [
                    'status' => 'success',
                    'toAmount'    => $toAmount,
                ];
        }
        
        $this->_helper->json($data);
    }

}

