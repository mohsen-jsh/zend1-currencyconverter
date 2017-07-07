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
    
    /**
     * save data to 'Previousexchangereq' table
     * @param array $data associate array of data to save in db
     */
    public function storePreviousexchangereq($data)
    {
        // insert method is safe(sql injection)
        $this->db->insert('previousexchangereq', $data);
    }
    
    /**
     * delete old cached exchange req and hold only last 5 rows
     */
    public function deleteOldPreviousexchangereq()
    {
        //$this->db->delete('previousexchangereq')->order('id desc')->limit(18446744073709551615, 5);
        $deleteQuery = "DELETE FROM `previousexchangereq` WHERE id IN (select id from (select id
                                           FROM `previousexchangereq`
                                       ORDER BY `id` DESC
                                          LIMIT 5, 2147483648) x)";
        $this->db->query($deleteQuery, 'execute');
    }
    
    /**
     * return latest currency Exchanges that user requested
     * @param integer $number number of rows to return
     * @return array
     */
    public function getPrevExchanges($number)
    {
        
        $select = $this->db->select()
             ->from(array('p' => 'previousexchangereq'),
                    array('amount_from', 'amount_to'))
            ->join(array('c1' => 'currencies'),
                    'p.currency_from_id = c1.id',
                    ['from_display_name' => 'display_name'] )
             ->join(array('c2' => 'currencies'),
                    'p.currency_to_id = c2.id',
                    ['to_display_name' => 'display_name'] );
        
        // get latest $number items
        $select->limit($number)->order('p.id desc');

        return $select->query()->fetchAll();
    }
    
    /**
     * store rates into exchanges db
     * @param array $rates array of rates like ['USD' => ['GBP', '']]
     */
    public function storeRates($rates)
    {        
        $query = 'INSERT INTO `exchanges` (`currency_from_id`, `currency_to_id`, `rate`, `created_at`) VALUES ';
        $queryVals = array();
        foreach ($rates as $row) {
            foreach($row as &$col) {
                $col = $this->db->quote($col);
            }
            $queryVals[] = '(' . implode(',', $row) . ')';
        }
        $this->db->query($query . implode(',', $queryVals));        
    }
}

