<?php

class Application_Form_ExchangeForm extends Zend_Form
{
    public function init()
    {
        $this->setAction('#')
             ->setMethod('post');

        // Create and configure amount element:
        $username = $this->createElement('text', 'amount');
        $username->addValidator('float')
                 ->setRequired(true);
        
        // Create and configure from element:
        $from = $this->createElement('text', 'from');
        $from->addValidator('digits')
                 ->setRequired(true);
        
        // Create and configure to element:
        $to = $this->createElement('text', 'to');
        $to->addValidator('digits')
                 ->setRequired(true);

        // Add elements to form:
        $this->addElement($username)
            ->addElement($from)
            ->addElement($to);
       /* $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));*/
    }
    
    
}