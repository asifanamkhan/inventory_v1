<?php

namespace App\Service;

class PaymentMethod
{
    public static $methods = [
        '1' => 'Cash',
        '2' => 'Mobile banking',
        '3' => 'Internet banking',
        '4' => 'Cheque',
        '5' => 'Bank transfer',
        '6' => 'Credit card',
        '7' => 'Debit card',
        '8' => 'Paypal',
        '9' => 'Other',
    ];

    public static function tranTypeCheck($prams){
        return self::$methods[$prams];
    }


}