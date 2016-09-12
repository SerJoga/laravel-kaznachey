<?php

return [
    /**
     * -----------------------------------
     * Merchant Secret Key
     * -----------------------------------
     *
     * Merchant secret key in Kaznachey
     */
    'merchant_secret_key' => 'C319F760-AA04-4C3A-A655-562543BF9EDC',

    /**
     * -----------------------------------
     * Merchant Guid
     * -----------------------------------
     *
     * Merchant individual ID in Kaznachey
     */
    'merchant_guid' => '4D312541-F967-4AD4-8D82-16D543ADB02E',

    /**
     * -----------------------------------
     * Payment currency
     * -----------------------------------
     *
     * Default payment currency in system
     * Can be:  UAH - gryvnya
     *          RUB - ruble
     *          USD - dollar
     *          EUR - euro
     */
    'currency' => 'USD',

    /**
     * -----------------------------------
     * Payment system
     * -----------------------------------
     *
     * Choose payment system which you need
     * Can be:  1 - for testing
     *          3, 8, 2022 - Visa / Mastercard
     *          4 - PrivatBank Terminal
     *          1020 - Privat24
     *          1021 - LiqPay
     */
    'payment_system' => 1,

    /**
     * -----------------------------------
     * Merchant configuration
     * -----------------------------------
     *
     * Can be true or false. Need to get secret key and guid.
     * Default: true
     * Can be:  true - get secret key and guid from config file
     *          false - get secret key and guid from database
     */
    'merchant_config' => true,
];
