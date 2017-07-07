<?php

class Application_Model_DbTable_Exchanges extends Zend_Db_Table_Abstract
{

    private $db; // Zend_Db_Table default handler
    public function __construct() 
    {
        $this->db = Zend_Db_Table::getDefaultAdapter();
    }
    protected $_name = 'exchanges';

    /**
     * get all currencies from db
     * @return array
     */
    public function getCurrencies() 
    {    	
    	$select = $this->db->select()
        ->from('currencies');

        return $select->query()->fetchAll();
    }
   
    /**
     * get latest rate for requested currency converting
     * @param integer $from currency_from_id
     * @param integer $to currency_to_id
     * @return string
     */
    public function getLatestExchangeRate($from, $to) 
    {    	
    	$select = $this->db->select()
        ->from('exchanges');

        $select->where("currency_from_id = ?", $from)
        ->where("currency_to_id = ?", $to)
        ->order('created_at desc');

        $result = $select->query()->fetch();

        return $result["rate"];
    }
}

