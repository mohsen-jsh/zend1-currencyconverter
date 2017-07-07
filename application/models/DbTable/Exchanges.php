<?php

class Application_Model_DbTable_Exchanges extends Zend_Db_Table_Abstract
{

    private $db; // Zend_Db_Table default handler
    public function __construct() 
    {
        $this->db = Zend_Db_Table::getDefaultAdapter();
    }
    protected $_name = 'exchanges';

    public function getCurrencies() 
    {    	
    	$select = $this->db->select()
        ->from('currencies');

        return $select->query()->fetchAll();
    }
   
}

