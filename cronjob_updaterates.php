<?php
// set this constant to specify this is just for cronjob (see public/index.php for more)
define("_CRONJOB_",true); 
require('public/index.php');


$exchangeService = new Application_Service_ExchangeService();
$exchangeService->updateExchangeRate();