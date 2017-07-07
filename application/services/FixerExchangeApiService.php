<?php

/**
 *
 * exchange rate api with http://fixer.io/
 */
class Application_Service_FixerExchangeApiService implements Application_Service_ExchangeApiInterface
{

    /**
     * get updated rate for specific currency from fixer.io API  
     * @param string $source
     * @return array
     */
    private function getRatesForSource($source){

        // Initialize CURL:
        $ch = curl_init('http://api.fixer.io/latest?base='.$source);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Store the data:
        $json = curl_exec($ch);
        curl_close($ch);

        // Decode JSON response:
        $exchangeRates = json_decode($json, true);

        // Access the exchange rate values, e.g. GBP:
        return $exchangeRates['rates'];
    }

    public function getAllRates($sources)
    {
        $rates=[];
        foreach($sources as $source) {
            $rates[$source] = $this->getRatesForSource($source);
        }

        return $rates;
    }

}