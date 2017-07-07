<?php

use Phinx\Seed\AbstractSeed;

class CurrenciesSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {

        $data = [
            [
                'symbol'    => 'AUD',
                'display_name' => 'AUD - Australian Dollar',
            ],
            [
                'symbol'    => 'BGN',
                'display_name' => 'BGN - Bulgarian Lev ',
            ],
            [
                'symbol'    => 'BRL',
                'display_name' => 'BRL - Brazilian Real',
            ],
            [
                'symbol'    => 'CAD',
                'display_name' => 'CAD - Canadian Dollar',
            ],
            [
                'symbol'    => 'CHF',
                'display_name' => 'CHF - Swiss Franc',
            ],
            [
                'symbol'    => 'CNY',
                'display_name' => 'CNY - Chinese Yuan Renminbi',
            ],
            [
                'symbol'    => 'CZK',
                'display_name' => 'CZK - Czech Koruna',
            ]           
        ];

        $currencies = $this->table('currencies');
        $currencies->insert($data)
              ->save();
    }
}
