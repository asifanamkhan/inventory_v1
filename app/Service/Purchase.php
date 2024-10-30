<?php

namespace App\Service;

class Purchase
{
    public static function purchaseStatus($prams){
        $value = '';
        if ($prams == 1){
            $value = 'Received';
        }
        elseif($prams == 2){
            $value = 'Partial';
        }
        elseif($prams == 3){
            $value = 'Pending';
        }
        elseif($prams == 4){
            $value = 'Ordered';
        }
        elseif($prams == 5){
            $value = 'Cancled';
        }

        return $value;
    }

}