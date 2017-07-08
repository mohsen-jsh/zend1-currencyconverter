Currency Converter
======

Currency Converter app with zend framework 1.12.20

Installation Guide
=====================

1. Clone Phinx from GitHub

    ```
    git clone https://github.com/mohsen-jsh/zend1-currencyconverter.git
    
    ```
    
2. config zend framework 1.12.20 library folder at /application/config/application.ini


3. change database settiong at /application/config/application.ini and /phinx.yml

4. run phinx migration

    ```
    php vendor/bin/phinx migrate -e development
    ```

5. rin phinx seed

    ```
    php vendor/bin/phinx seed:run
    ```
    
- /public url for currency converter form
- /cronjob_updaterates.php file to execute cron job (update currency rate from http://fixer.io/)
