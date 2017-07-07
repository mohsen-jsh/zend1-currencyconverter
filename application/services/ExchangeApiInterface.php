<?php

interface Application_Service_ExchangeApiInterface {

	/**
	 * return all updated rates
	 * @return assouciate array of $source name as key and rates as values
	 *
	 */
	public function getAllRates($sources);
}