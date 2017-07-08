<?php

class IndexController extends Zend_Controller_Action
{

    private $exchangeService; // do all currency converting with this class object
    public function init()
    {
        $this->exchangeService = new Application_Service_ExchangeService();
        $this->getResponse()->setHeader('Expires', '', true);
        $this->getResponse()->setHeader('Cache-Control', 'public', true);
        $this->getResponse()->setHeader('Cache-Control', 'max-age=31536000');
        $this->getResponse()->setHeader('Pragma', '', true);
    }

    public function indexAction()
    {
        $currencies = $this->exchangeService->getCurrencies();
        $this->view->currencies = $currencies;
        
        // get 5 latest crrency exchange requests
        $prevExchanges = $this->exchangeService->getPrevExchanges(5);
        $this->view->prevExchanges = $prevExchanges;
        
        $this->view->form = new Application_Form_ExchangeForm();
        
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

            $this->exchangeService->cacheResult($from, $to, $fromAmount, $toAmount);
                
            $data = [
                    'status' => 'success',
                    'toAmount'    => $toAmount,
                ];
        }
        
        // render new form to set new csrf hash token in session
        $form2 = new Application_Form_ExchangeForm();
        $form2->render();
        $data['token'] = $_SESSION['Zend_Form_Element_Hash_salt_csrf'];
        
        $this->_helper->json($data);
    }

}

