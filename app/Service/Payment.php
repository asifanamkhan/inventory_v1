<?php

namespace App\Service;

class Payment
{
    public static function PaymentCheck($prams){
        if($prams > 0){
            return 'DUE';
        }
        else{
            return 'PAID';
        }
    }

    public static function ReturnPaymentCheck($prams){
        if($prams > 0){
            return 'DUE';
        }
        else{
            return 'RECEIVED';
        }
    }

    public static function paymentSatus($total, $return, $payment){
        $cal = (float)$total- ((float)$return + (float)$payment);
        if($cal > 0){
            return 'DUE';
        }else{
            return 'PAID';
        }
    }

    public static function dueAmount($total, $return, $payment){
        $cal = (float)$total- ((float)$return + (float)$payment);
        return $cal;
    }
}