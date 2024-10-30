<?php

namespace App\Service;

class Accounts
{
    public static $tranTypeArray = [
        'PR' => 'Purchase',
        'PRT' => 'Purchase return',
        'SL' => 'Sale',
        'SRT' => 'Sale return',
        'EXP' => 'Expense',
    ];

    public static function tranTypeCheck($prams){
        return self::$tranTypeArray[$prams];
    }


}